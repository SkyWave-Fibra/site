<?php

namespace Source\App;

use Source\Core\Controller;
use Source\Models\App\Equipment;
use Source\Models\Auth;
use Source\Models\Report\Access;
use Source\Models\Report\Online;
use Source\Models\User;
use Source\Support\Thumb;
use Source\Support\Upload;

/**
 * APP | Controller
 * @package Source\App
 */
class App extends Controller
{
    /** @var Account */
    private $user;

    /** APP | Constructor */
    public function __construct()
    {
        parent::__construct(__DIR__ . "/../../themes/" . CONF_VIEW_APP . "/");

        if (!$this->user = Auth::account()) {
            $this->message->warning("Efetue login para acessar o APP.")->toast()->flash();
            redirect("/entrar");
        }

        (new Access())->report();
        (new Online())->report();
    }

    /**
     * Renderiza a página com os dados fornecidos.
     *
     * Esta função configura os metadados da página (SEO) e renderiza o template especificado.
     *
     * @param string $templateName Nome do template a ser renderizado.
     * @param array|null $data Dados a serem passados para a view (opcional).
     * @param string|null $headTitle Título da página (opcional).
     * @param string|null $headDescription Descrição da página (opcional).
     * @param string|null $headUrl URL da página (opcional).
     * @param string|null $headImage Imagem de compartilhamento da página (opcional).
     * @param bool $headFollow Indica se os motores de busca devem seguir os links da página (padrão: true).
     * @return void
     */
    private function renderPage(
        string $templateName,
        ?array $data = [],
        ?string $headTitle = null,
        ?string $headDescription = null,
        ?string $headUrl = null,
        ?string $headImage = null,
        bool $headFollow = true
    ): void {
        // Gera os metadados para SEO
        $head = $this->seo->render(
            $headTitle ?? CONF_SITE_NAME,
            $headDescription ?? CONF_SITE_DESC,
            $headUrl ?? url("/app"),
            $headImage ?? url("/shared/assets/images/share.png"),
            $headFollow
        );

        // Garante que $data seja um array antes de modificar
        $data = array_merge(["head" => $head], $data ?? []);

        // Renderiza a página
        echo $this->view->render($templateName, $data);
    }

    /** APP | Home */
    public function home(): void
    {
        $this->renderPage("home", [
            "active"      => "home",
            "title"       => "Início",
            "subtitle"    => "Bem-vindo(a)!",
        ]);
    }

    /** APP | Equipamentos */

    public function equipments(): void
    {
        $this->renderPage("equipments", [
            "active"      => "equipments",
            "title"       => "Equipamentos",
            "subtitle"    => "Gerencie seus equipamentos",
            "equipments" => (new Equipment())->find()->fetch(true) ?? [],
        ]);
    }


    public function equipment(): void
    {
        $this->renderPage("equipment", [
            "active"      => "equipment",
            "title"       => "Equipamentos",
            "subtitle"    => "Gerencie seus equipamentos",
        ]);
    }

    /**
     * APP | Edita Equipamento
     * @param array $data
     * @return void
     */
    public function editEquipment(array $data): void
    {
        $equipmentId = filter_var($data['id'], FILTER_VALIDATE_INT);
        $equipment = (new Equipment())->findById($equipmentId);

        if (!$equipment) {
            $this->message->error("Equipamento não encontrado!")->toast()->flash();
            redirect("/app/equipamentos");
        }

        $this->renderPage("editEquipment", [
            "active"      => "equipments",
            "title"       => "Editar Equipamento",
            "subtitle"    => "Edite os dados do equipamento",
            "equipment"   => $equipment
        ]);
    }

    /**
     * APP | Salva ou Atualiza Equipamento
     * @param array $data
     * @return void
     */
    public function saveEquipment(array $data): void
    {
        $data = filter_var_array($data, FILTER_UNSAFE_RAW);

        $equipmentId = null;
        if (!empty($data['id']) && $data['_method'] === 'PUT') {
            $equipmentId = filter_var($data['id'], FILTER_VALIDATE_INT);
        }

        $equipment = ($equipmentId ? (new Equipment())->findById($equipmentId) : new Equipment());

        if (!$equipment) {
            jsonResponse([
                "success" => false,
                "message" => $this->message->error("Equipamento não encontrado para atualização.")->toast()->render()
            ]);
            return;
        }

        $equipment->type = $data['type'] ?? '';
        $equipment->manufacturer = $data['manufacturer'] ?? '';
        $equipment->model = $data['model'] ?? '';
        $equipment->serial_number = $data['serial_number'] ?? '';
        $equipment->status = $data['status'] ?? '';

        if (!$equipment->save()) {
            jsonResponse([
                "success" => false,
                "message" => ($equipment->message() ?: $this->message)
                    ->error("Erro ao salvar o equipamento.")->toast()->render()
            ]);
            return;
        }

        $message = $equipmentId ? "Equipamento atualizado com sucesso!" : "Equipamento cadastrado com sucesso!";
        $this->message->success($message)->toast()->flash();

        jsonResponse([
            "success"  => true,
            "message"  => $this->message->success($message)->toast()->render(),
            "redirect" => url("/app/equipamentos")
        ]);
    }

    // Usuários

    /**
     * APP | Exclui Equipamento
     * @param array $data
     * @return void
     */
    public function deleteEquipment(array $data): void
    {
        $equipmentId = filter_var($data['id'], FILTER_VALIDATE_INT);
        $equipment = (new Equipment())->findById($equipmentId);

        if (!$equipment) {
            jsonResponse([
                "success" => false,
                "message" => $this->message->error("Equipamento não encontrado para exclusão.")->toast()->render()
            ]);
            return;
        }

        if (!$equipment->destroy()) {
            jsonResponse([
                "success" => false,
                "message" => ($equipment->message() ?: $this->message)
                    ->error("Erro ao excluir o equipamento.")->toast()->render()
            ]);
            return;
        }

        $this->message->success("Equipamento excluído com sucesso!")->toast()->flash();

        jsonResponse([
            "success"  => true,
            "message"  => $this->message->success("Equipamento excluído com sucesso!")->toast()->render(),
            "redirect" => url("/app/equipamentos")
        ]);
    }

    // Usuários

    /** APP | Perfil */
    public function profile(): void
    {
        $this->renderPage("profile", [
            "active"      => "profile",
            "title"       => "Perfil",
            "subtitle"    => "Gerencie seu perfil",
            "user"        => $this->user
        ]);
    }

    public function profileSave(array $data): void
    {
        $user = $this->user; // Account
        $person = $user->person();

        // === Upload da foto de perfil ===
        if (!empty($_FILES["photo"]) and $_FILES["photo"]["size"] > 0) {
            $file = $_FILES["photo"];
            $upload = new Upload();

            // Remove imagem anterior
            if (!empty($user->avatar)) {
                (new Thumb())->flush("storage/{$user->avatar}");
                $upload->remove("storage/{$user->avatar}");
            }

            // Faz upload da nova
            if (!$avatarPath = $upload->image($file, "{$person->full_name}-" . time(), 360)) {
                $json["message"] = $upload->message()
                    ->before("Ooops {$person->shortName()}! ")
                    ->after(".")
                    ->toast()
                    ->render();
                echo json_encode($json);
                return;
            }

            $user->avatar = $avatarPath;
        }

        // === Atualiza dados da pessoa ===
        $person->full_name   = $data["full_name"] ?? $person->full_name;
        $person->document    = $data["document"] ?? $person->document;
        $person->person_type = $data["person_type"] ?? $person->person_type;
        $person->birth_date  = !empty($data["birth_date"]) ? $data["birth_date"] : $person->birth_date;
        $person->save();

        // === Atualiza e-mail ===
        if (!empty($data["email"])) {
            $user->email = $data["email"];
        }
        $user->save();

        // === Atualiza contatos ===
        foreach (["phone", "whatsapp"] as $type) {
            $value = trim($data[$type] ?? "");
            if (empty($value)) {
                continue;
            }

            $contact = (new \Source\Models\App\Contact())
                ->find("person_id = :pid AND contact_type = :t", "pid={$person->id}&t={$type}")
                ->fetch();

            if (!$contact) {
                $contact = new \Source\Models\App\Contact();
                $contact->person_id = $person->id;
                $contact->contact_type = $type;
            }

            $contact->value = $value;
            $contact->save();
        }

        // === Atualiza endereço ===
        $address = $person->address() ?? new \Source\Models\App\Address();

        $address->street     = $data["street"]     ?? $address->street;
        $address->number     = $data["number"]     ?? $address->number;
        $address->district   = $data["district"]   ?? $address->district;
        $address->city       = $data["city"]       ?? $address->city;
        $address->state      = !empty($data["state"]) ? strtoupper($data["state"]) : $address->state;
        $address->zipcode    = $data["zipcode"]    ?? $address->zipcode;
        $address->complement = $data["complement"] ?? $address->complement;
        $address->save();

        // Vincula endereço à pessoa (caso ainda não exista)
        if (!$person->address()) {
            $pa = new \Source\Models\App\PersonAddress();
            $pa->person_id    = $person->id;
            $pa->address_id   = $address->id;
            $pa->address_type = "billing";
            $pa->save();
        }

        // === Resposta ===
        $json["success"] = true;
        $json["message"] = $this->message->success("Perfil atualizado com sucesso!")->toast()->render();
        echo json_encode($json);
    }



    /** APP | Logout */
    public function logout(): void
    {
        Auth::logout();
        redirect("/entrar");
    }
}
