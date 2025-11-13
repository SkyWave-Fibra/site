<?php

namespace Source\App;

use PDOException;
use Source\Core\Controller;
use Source\Models\Account;
use Source\Models\App\Equipment;
use Source\Models\App\Plan;
use Source\Models\App\Contract;
use Source\Models\App\Customer;
use Source\Models\App\Employee;
use Source\Models\App\SupportTicket;
use Source\Models\App\TicketHistory;
use Source\Models\App\TicketComment;
use Source\Models\App\TicketAttachment;
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
    /**
     * APP HOME (PÁGINA PRINCIPAL DO CLIENTE)
     */
    public function home(): void
    {
        $user = Auth::account();

        // =============================
        // PLANO ATUAL DO USUÁRIO LOGADO
        // =============================
        $current_plan = null;
        $suggested_plan = null;

        $contract = (new \Source\Models\App\Contract())->find(
            "customer_id = :uid AND status = 'active'",
            "uid={$user->id}"
        )->fetch();

        if ($contract) {
            $current_plan = $contract->plan();
            if ($current_plan) {
                $suggested_plan = (new \Source\Models\App\Plan())->find(
                    "price > :p",
                    "p={$current_plan->price}"
                )->order("price ASC")->fetch();
            }
        }

        // Verifica se o usuário é funcionário
        $isEmployee = (new Employee())->find("person_id = :pid AND status = 'active'", "pid={$user->person_id}")->fetch();

        // =============================
        // TODOS OS FUNCIONÁRIOS
        // =============================
        $employees = (new \Source\Models\App\Employee())
            ->find("status = 'active'")
            ->fetch(true) ?? [];

        // =============================
        // CLIENTES AGRUPADOS POR PLANOS (USANDO CONTRACT)
        // =============================
        $plans = (new \Source\Models\App\Plan())->find()->fetch(true) ?? [];
        $plansWithContracts = [];

        foreach ($plans as $plan) {

            // Contratos ativos deste plano
            $contracts = (new \Source\Models\App\Contract())
                ->find("plan_id = :pid AND status = 'active'", "pid={$plan->id}")
                ->fetch(true) ?? [];

            $plansWithContracts[] = [
                "plan"      => $plan,
                "contracts" => $contracts
            ];
        }

        // =============================
        // RENDERIZAÇÃO
        // =============================
        $this->renderPage("home", [
            "active"            => "home",
            "activeMenu"        => "cliente",
            "title"             => "Início",
            "subtitle"          => "Bem-vindo(a)!",
            "current_plan"      => $current_plan,
            "suggested_plan"    => $suggested_plan,
            "employees"         => $employees,
            "plansWithContracts" => $plansWithContracts,
            "isEmployee"        => $isEmployee
        ]);
    }

    public function serviceStatus(): void
    {
        // ATENÇÃO: A VIEW CHAMA AGORA "status", esperando o arquivo 'status.php'
        $this->renderPage("status", [
            "active"      => "status",
            "title"       => "Status do Serviço",
            "subtitle"    => "Acompanhe a disponibilidade de nossos serviços",
            // Aqui você pode buscar dados de status de serviços e passá-los para a view
        ]);
    }

    /**
     * APP | Salva ou Atualiza Status de Serviço (via AJAX)
     * @param array $data
     * @return void
     */
    public function saveServiceStatusPost(?array $data): void
    {
        header('Content-Type: application/json'); // Garante que a resposta seja tratada como JSON
        $json = [];
        $pdo = \Source\Core\Connect::getInstance(); // Obter a instância PDO

        $id = $data['id'] ?? null;
        $customer_id = $data['customer_id'] ?? null;
        $status = $data['status'] ?? null;
        $reason = $data['reason'] ?? null;

        // Validação básica
        if (empty($customer_id) || empty($status)) {
            $json["message"] = "Cliente e Status são obrigatórios.";
            $json["type"] = "warning";
            echo json_encode($json);
            die(); // Garante que nada mais seja enviado
        }

        // Validação: Verificar se o customer_id existe na tabela customer
        try {
            $stmtCheckCustomer = $pdo->prepare("SELECT COUNT(*) FROM customer WHERE person_id = ?");
            $stmtCheckCustomer->execute([$customer_id]);
            if ($stmtCheckCustomer->fetchColumn() == 0) {
                $json["message"] = "Erro: O cliente selecionado não é válido ou não está registrado como cliente.";
                $json["type"] = "danger";
                echo json_encode($json);
                die(); // Garante que nada mais seja enviado
            }
        } catch (PDOException $e) {
            error_log("Erro PDO na validação de cliente em App.php: " . $e->getMessage());
            $json["message"] = "Erro interno ao validar cliente.";
            $json["type"] = "danger";
            echo json_encode($json);
            die(); // Garante que nada mais seja enviado
        }

        try {
            if (empty($id)) {
                // Verificar se o cliente já possui um status
                $stmtCheckExisting = $pdo->prepare("SELECT id, customer_id, status, reason FROM service_status WHERE customer_id = ?");
                $stmtCheckExisting->execute([$customer_id]);
                $existingStatus = $stmtCheckExisting->fetch(\PDO::FETCH_ASSOC); // Buscar o registro completo

                if ($existingStatus) {
                    $json["message"] = "Este cliente já possui um status registrado. O formulário de edição foi carregado automaticamente.";
                    $json["type"] = "warning";
                    // Adicionar dados do status existente para o frontend abrir o modal de edição
                    $json["existing_status"] = [
                        "id" => $existingStatus['id'],
                        "customer_id" => $existingStatus['customer_id'],
                        "status" => $existingStatus['status'],
                        "reason" => $existingStatus['reason']
                    ];
                    echo json_encode($json);
                    die(); // Garante que nada mais seja enviado
                }

                // Inserir novo status
                $stmt = $pdo->prepare("INSERT INTO service_status (customer_id, status, reason, changed_at) VALUES (?, ?, ?, NOW())");
                $stmt->execute([$customer_id, $status, $reason]);
                $json["message"] = "Status adicionado com sucesso!";
                $json["type"] = "success";
            } else {
                // Atualizar status existente
                // Ao atualizar, não precisamos verificar duplicidade de customer_id, pois estamos modificando um registro existente.
                $stmt = $pdo->prepare("UPDATE service_status SET customer_id=?, status=?, reason=?, changed_at=NOW() WHERE id=?");
                $stmt->execute([$customer_id, $status, $reason, $id]);
                $json["message"] = "Status atualizado com sucesso!";
                $json["type"] = "success";
            }
            $json["redirect"] = url("/app/status"); // Redireciona para recarregar a lista
        } catch (PDOException $e) {
            error_log("Erro PDO ao salvar status em App.php: " . $e->getMessage() . " (Código: " . $e->getCode() . ")");
            $json["message"] = "Erro PDO ao salvar status: " . $e->getMessage();
            $json["type"] = "danger";
        }

        echo json_encode($json);
        die(); // Adicionado para garantir que nada mais seja enviado
    }

    /**
     * APP | Exclui Status de Serviço (via AJAX)
     * @param array $data
     * @return void
     */
    public function deleteServiceStatus(?array $data): void
    {
        $json = [];
        $pdo = \Source\Core\Connect::getInstance();

        $id = $data['id'] ?? null;

        if (empty($id)) {
            $json["message"] = $this->message->warning("ID do status não fornecido para exclusão.")->toast()->render();
            echo json_encode($json);
            return;
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM service_status WHERE id = ?");
            $stmt->execute([$id]);
            $json["message"] = $this->message->success("Status excluído com sucesso!")->toast()->render();
            $json["redirect"] = url("/app/status"); // Redireciona para recarregar a lista
        } catch (PDOException $e) {
            error_log("Erro PDO ao excluir status em App.php: " . $e->getMessage());
            $json["message"] = $this->message->error("Erro PDO ao excluir status: " . $e->getMessage())->toast()->render();
        }

        echo json_encode($json);
    }

    /**
     * APP | Retorna dados de Status de Serviço para DataTables (via AJAX)
     * @return void
     */
    public function getServiceStatusData(): void
    {
        header('Content-Type: application/json');
        $json = ["data" => []];
        $pdo = \Source\Core\Connect::getInstance();

        // Parâmetros do DataTables
        $draw = $_GET['draw'] ?? 1;
        $start = $_GET['start'] ?? 0;
        $length = $_GET['length'] ?? 10;
        $searchValue = $_GET['search']['value'] ?? '';

        $recordsTotal = 0;
        $recordsFiltered = 0;

        try {
            // Contar total de registros (sem filtro)
            $stmtTotal = $pdo->query("SELECT COUNT(*) FROM service_status");
            $recordsTotal = $stmtTotal->fetchColumn();

            $whereClause = "";
            $params = [];

            if (!empty($searchValue)) {
                $whereClause = " WHERE p.full_name LIKE :search OR ss.reason LIKE :search OR ss.status LIKE :search";
                $params[':search'] = '%' . $searchValue . '%';
            }

            // Contar registros filtrados
            $stmtFiltered = $pdo->prepare(
                "
                SELECT COUNT(ss.id)
                FROM service_status ss
                JOIN customer c ON c.person_id = ss.customer_id
                JOIN person p ON p.id = c.person_id
                " . $whereClause
            );
            $stmtFiltered->execute($params);
            $recordsFiltered = $stmtFiltered->fetchColumn();

            // Obter dados com paginação e filtro
            $sqlQuery = "
                SELECT ss.id, p.full_name AS cliente, ss.status, ss.reason, ss.changed_at, ss.customer_id
                FROM service_status ss
                JOIN customer c ON c.person_id = ss.customer_id
                JOIN person p ON p.id = c.person_id
                " . $whereClause . "
                ORDER BY ss.id DESC
                LIMIT :start, :length
            ";
            $stmt = $pdo->prepare($sqlQuery);
            $stmt->bindValue(':start', (int)$start, \PDO::PARAM_INT);
            $stmt->bindValue(':length', (int)$length, \PDO::PARAM_INT);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value, \PDO::PARAM_STR);
            }
            $stmt->execute();
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $data = [];
            foreach ($rows as $row) {
                $statusClassMap = [
                    'active' => 'badge-light-success',
                    'suspended' => 'badge-light-warning',
                    'blocked' => 'badge-light-danger',
                    'canceled' => 'badge-light-secondary'
                ];
                $statusClass = $statusClassMap[$row['status']] ?? 'badge-light-info';
                $statusHtml = '<span class="badge ' . $statusClass . '">' . ucfirst($row['status']) . '</span>';

                $actionsHtml = '
                    <button class="btn btn-sm btn-light btn-active-light-primary me-2 btn-editar"
                        data-id="' . $row['id'] . '"
                        data-customer="' . $row['customer_id'] . '"
                        data-status="' . $row['status'] . '"
                        data-reason="' . htmlspecialchars($row['reason']) . '">
                        <i class="ki-outline ki-pencil fs-5"></i> Editar
                    </button>
                    <button class="btn btn-sm btn-light-danger btn-excluir"
                        data-id="' . $row['id'] . '">
                        <i class="ki-outline ki-trash fs-5"></i> Excluir
                    </button>';

                $data[] = [
                    $row['id'],
                    htmlspecialchars($row['cliente']),
                    $statusHtml,
                    htmlspecialchars($row['reason']),
                    date("d/m/Y H:i", strtotime($row['changed_at'])),
                    $actionsHtml,
                    $row['customer_id'] // Adiciona customer_id para ser usado no createdRow
                ];
            }

            $json = [
                "draw" => (int)$draw,
                "recordsTotal" => (int)$recordsTotal,
                "recordsFiltered" => (int)$recordsFiltered,
                "data" => $data
            ];
        } catch (PDOException $e) {
            error_log("Erro PDO ao buscar dados de status para DataTables em App.php: " . $e->getMessage());
            $json["error"] = "Erro ao carregar dados: " . $e->getMessage();
            // Em caso de erro, ainda retornar um JSON válido para o DataTables
            $json = [
                "draw" => (int)$draw,
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => $json["error"]
            ];
        }

        echo json_encode($json);
        die(); // Garante que nenhum outro output PHP seja enviado
    }

    // Equipamentos
    public function equipments(?array $data): void
    {
        $session = new \Source\Core\Session();

        // 🔹 1. Se for POST: salva busca e redireciona
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $search = trim($data["search"] ?? "");

            if ($search !== "") {
                $session->set("equipment_search", $search);
            } else {
                $session->unset("equipment_search");
            }

            echo json_encode(["redirect" => url("/app/equipamentos")]);
            return;
        }

        // 🔹 2. Se vier GET com ?clear=1, limpa a busca
        if (!empty($_GET["clear"])) {
            $session->unset("equipment_search");
        }

        // 🔹 3. Busca persistente
        $search = $session->has("equipment_search") ? $session->equipment_search : "";

        // 🔹 4. Paginação
        $page  = (int)($data["page"] ?? 1);
        $limit = (int)($data["limit"] ?? 10);

        // 🔹 5. Query
        $equipmentModel = new \Source\Models\App\Equipment();

        if (!empty($search)) {
            $query = $equipmentModel->find(
                "(manufacturer LIKE CONCAT('%', :search, '%')
              OR model LIKE CONCAT('%', :search, '%')
              OR serial_number LIKE CONCAT('%', :search, '%'))",
                "search={$search}"
            );
        } else {
            $query = $equipmentModel->find();
        }

        $total = $query->count();
        $equipments = $query->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
        $pages = ceil($total / $limit);

        $this->renderPage("equipments/main", [
            "title"      => "Equipamentos",
            "equipments" => $equipments,
            "search"     => $search,
            "page"       => $page,
            "pages"      => $pages,
            "limit"      => $limit,
            "total"      => $total,
            "activeMenu" => "admin",
            "activesubmenu" => "admequipamentos",
        ]);
    }

    public function equipment(?array $data): void
    {
        $isEdit = false;
        $equipment = new \Source\Models\App\Equipment();

        // 🔹 Edição
        if (!empty($data["id"])) {
            $equipment = (new \Source\Models\App\Equipment())->findById((int)$data["id"]);
            if (!$equipment) {
                (new \Source\Support\Message())->error("Equipamento não encontrado.")->flash();
                redirect("/app/equipamentos");
                return;
            }

            $isEdit = true;
        }

        // Busca alocação ativa (se houver)
        $allocation = (new \Source\Models\App\CustomerEquipment())
            ->find(
                "equipment_id = :id AND (end_date IS NULL OR end_date = '0000-00-00')",
                "id={$equipment->id}"
            )
            ->fetch();

        $allocatedCustomer = null;

        if ($allocation) {
            $allocatedCustomer = (new \Source\Models\App\Customer())
                ->find("person_id = :pid", "pid={$allocation->customer_id}")
                ->fetch();
        }


        $this->renderPage("equipments/form", [
            "title"       => $isEdit ? "Editar Equipamento" : "Novo Equipamento",
            "subtitle"    => $isEdit ? "Atualize as informações do equipamento" : "Cadastre um novo equipamento",
            "equipment"   => $equipment,
            "isEdit"      => $isEdit,
            "activeMenu"  => "admin",
            "activesubmenu" => "admequipamentos",
            "allocatedCustomer" => $allocatedCustomer
        ]);
    }

    public function saveEquipmentPost(?array $data): void
    {
        $json = [];

        // 🔹 Edição ou novo
        if (!empty($data["id"])) {
            $equipment = (new \Source\Models\App\Equipment())->findById($data["id"]);
            if (!$equipment) {
                $json["message"] = (new \Source\Support\Message())
                    ->error("Equipamento não encontrado.")
                    ->toast()
                    ->render();
                echo json_encode($json);
                return;
            }
        } else {
            $equipment = new \Source\Models\App\Equipment();
        }

        // 🔹 Limpa e prepara dados
        $type          = $data["type"] ?? null;
        $manufacturer  = trim($data["manufacturer"] ?? "");
        $model         = trim($data["model"] ?? "");
        $serialNumber  = trim($data["serial_number"] ?? "");
        $status        = $data["status"] ?? "available";

        // 🔹 Verifica duplicidade de serial
        $serialExists = (new \Source\Models\App\Equipment())
            ->find("serial_number = :sn AND id != :id", "sn={$serialNumber}&id=" . ($equipment->id ?? 0))
            ->count();

        if ($serialExists > 0) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("O número de série informado já está cadastrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Atualiza / cria
        $equipment->type          = $type;
        $equipment->manufacturer  = $manufacturer;
        $equipment->model         = $model;
        $equipment->serial_number = $serialNumber;
        $equipment->status        = $status;

        if (!$equipment->save()) {
            $json["message"] = $equipment->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        $json["message"] = (new \Source\Support\Message())
            ->success("Equipamento " . (!empty($data["id"]) ? "atualizado" : "criado") . " com sucesso!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/equipamentos");
        echo json_encode($json);
    }

    public function deleteEquipment(?array $data): void
    {
        $json = [];

        $id = (int)($data["id"] ?? 0);

        if (!$id) {
            (new \Source\Support\Message())
                ->error("ID inválido.")
                ->toast()
                ->flash();

            $json["redirect"] = url("/app/equipamentos");
            jsonResponse($json);
            return;
        }

        // 🔍 Busca equipamento
        $equipment = (new \Source\Models\App\Equipment())->findById($id);

        if (!$equipment) {
            (new \Source\Support\Message())
                ->error("Equipamento não encontrado.")
                ->toast()
                ->flash();

            $json["redirect"] = url("/app/equipamentos");
            jsonResponse($json);
            return;
        }

        // 🔒 Verificar se está alocado a algum cliente
        $linked = (new \Source\Models\App\CustomerEquipment())
            ->find(
                "equipment_id = :id AND (end_date IS NULL OR end_date = '0000-00-00')",
                "id={$id}"
            )
            ->fetch();

        if ($linked) {

            (new \Source\Support\Message())
                ->warning("Este equipamento está alocado a um cliente. Libere o equipamento antes de excluir.")
                ->toast()
                ->flash();

            $json["redirect"] = url("/app/equipamentos");
            jsonResponse($json);
            return;
        }

        // 🗑️ Excluir definitivamente
        $equipment->destroy();

        (new \Source\Support\Message())
            ->success("Equipamento excluído com sucesso!")
            ->toast()
            ->flash();

        $json["redirect"] = url("/app/equipamentos");
        jsonResponse($json);
    }


    // public function equipments(): void
    // {
    //     $this->renderPage("equipments", [
    //         "active"      => "equipments",
    //         "title"       => "Equipamentos",
    //         "subtitle"    => "Gerencie seus equipamentos",
    //         "equipments" => (new Equipment())->find()->fetch(true) ?? [],
    //     ]);
    // }

    // public function equipment(): void
    // {
    //     $this->renderPage("equipment", [
    //         "active"      => "equipment",
    //         "title"       => "Equipamentos",
    //         "subtitle"    => "Gerencie seus equipamentos",
    //     ]);
    // }

    // public function editEquipment(array $data): void
    // {
    //     $equipmentId = filter_var($data['id'], FILTER_VALIDATE_INT);
    //     $equipment = (new Equipment())->findById($equipmentId);

    //     if (!$equipment) {
    //         $this->message->error("Equipamento não encontrado!")->toast()->flash();
    //         redirect("/app/equipamentos");
    //     }

    //     $this->renderPage("editEquipment", [
    //         "active"      => "equipments",
    //         "title"       => "Editar Equipamento",
    //         "subtitle"    => "Edite os dados do equipamento",
    //         "equipment"   => $equipment
    //     ]);
    // }

    // public function saveEquipment(array $data): void
    // {
    //     $data = filter_var_array($data, FILTER_UNSAFE_RAW);

    //     $equipmentId = null;
    //     if (!empty($data['id']) && $data['_method'] === 'PUT') {
    //         $equipmentId = filter_var($data['id'], FILTER_VALIDATE_INT);
    //     }

    //     $equipment = ($equipmentId ? (new Equipment())->findById($equipmentId) : new Equipment());

    //     if (!$equipment) {
    //         jsonResponse([
    //             "success" => false,
    //             "message" => $this->message->error("Equipamento não encontrado para atualização.")->toast()->render()
    //         ]);
    //         return;
    //     }

    //     $equipment->type = $data['type'] ?? '';
    //     $equipment->manufacturer = $data['manufacturer'] ?? '';
    //     $equipment->model = $data['model'] ?? '';
    //     $equipment->serial_number = $data['serial_number'] ?? '';
    //     $equipment->status = $data['status'] ?? '';

    //     if (!$equipment->save()) {
    //         jsonResponse([
    //             "success" => false,
    //             "message" => ($equipment->message() ?: $this->message)
    //                 ->error("Erro ao salvar o equipamento.")->toast()->render()
    //         ]);
    //         return;
    //     }

    //     $message = $equipmentId ? "Equipamento atualizado com sucesso!" : "Equipamento cadastrado com sucesso!";
    //     $this->message->success($message)->toast()->flash();

    //     jsonResponse([
    //         "success"  => true,
    //         "message"  => $this->message->success($message)->toast()->render(),
    //         "redirect" => url("/app/equipamentos")
    //     ]);
    // }

    // public function deleteEquipment(array $data): void
    // {
    //     $equipmentId = filter_var($data['id'], FILTER_VALIDATE_INT);
    //     $equipment = (new Equipment())->findById($equipmentId);

    //     if (!$equipment) {
    //         jsonResponse([
    //             "success" => false,
    //             "message" => $this->message->error("Equipamento não encontrado para exclusão.")->toast()->render()
    //         ]);
    //         return;
    //     }

    //     if (!$equipment->destroy()) {
    //         jsonResponse([
    //             "success" => false,
    //             "message" => ($equipment->message() ?: $this->message)
    //                 ->error("Erro ao excluir o equipamento.")->toast()->render()
    //         ]);
    //         return;
    //     }

    //     $this->message->success("Equipamento excluído com sucesso!")->toast()->flash();

    //     jsonResponse([
    //         "success"  => true,
    //         "message"  => $this->message->success("Equipamento excluído com sucesso!")->toast()->render(),
    //         "redirect" => url("/app/equipamentos")
    //     ]);
    // }

    //Funcionários
    // Funcionários
    public function employees(?array $data): void
    {
        $session = new \Source\Core\Session();

        // 🔹 1. POST → salva busca e redireciona
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $search = trim($data["search"] ?? "");

            if ($search !== "") {
                $session->set("employee_search", $search);
            } else {
                $session->unset("employee_search");
            }

            echo json_encode(["redirect" => url("/app/funcionarios")]);
            return;
        }

        // 🔹 2. Limpa busca
        if (!empty($_GET["clear"])) {
            $session->unset("employee_search");
        }

        // 🔹 3. Busca persistente
        $search = $session->has("employee_search") ? $session->employee_search : "";

        // 🔹 4. Paginação
        $page  = (int)($data["page"] ?? 1);
        $limit = (int)($data["limit"] ?? 10);

        // 🔹 5. Query
        $employeeModel = new \Source\Models\App\Employee();

        if (!empty($search)) {
            $query = $employeeModel->find(
                "person_id IN (
                SELECT id FROM person 
                WHERE full_name LIKE CONCAT('%', :search, '%') 
                OR document LIKE CONCAT('%', :search, '%')
            )",
                "search={$search}"
            );
        } else {
            $query = $employeeModel->find();
        }

        $total = $query->count();
        $employees = $query->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
        $pages = ceil($total / $limit);

        $this->renderPage("employees/main", [
            "title"      => "Funcionários",
            "employees"  => $employees,
            "search"     => $search,
            "page"       => $page,
            "pages"      => $pages,
            "limit"      => $limit,
            "total"      => $total,
            "activeMenu" => "admin",
            "activesubmenu" => "admfuncionarios"
        ]);
    }

    public function employee(?array $data): void
    {
        $isEdit = false;
        $employee = new \Source\Models\App\Employee();
        $person = new \Source\Models\Person();
        $employee->person = $person;



        if (!empty($data["id"])) {
            /** @var \Source\Models\App\Employee|null $employee */
            $employee = (new \Source\Models\App\Employee())->findById((int)$data["id"]);
            if (!$employee) {
                (new \Source\Support\Message())->error("Funcionário não encontrado.")->flash();
                redirect("/app/funcionarios");
                return;
            }

            $isEdit = true;
            $person = $employee->person();
            $employee->person = $person;
        }

        $this->renderPage("employees/form", [
            "title"       => $isEdit ? "Editar Funcionário" : "Novo Funcionário",
            "subtitle"    => $isEdit ? "Atualize as informações do funcionário" : "Cadastre um novo funcionário",
            "employee"    => $employee,
            "isEdit"      => $isEdit,
            "activeMenu"  => "admin",
            "activesubmenu"  => "admfuncionarios"
        ]);
    }

    public function saveEmployeePost(?array $data): void
    {
        $json = [];

        if (!empty($data["person_id"])) {
            /** @var \Source\Models\App\Employee|null $employee */
            $employee = (new \Source\Models\App\Employee())->findById($data["person_id"]);
            if (!$employee) {
                $json["message"] = (new \Source\Support\Message())
                    ->error("Funcionário não encontrado.")
                    ->toast()
                    ->render();
                echo json_encode($json);
                return;
            }
            $person = $employee->person();
        } else {
            $employee = new \Source\Models\App\Employee();
            $person = new \Source\Models\Person();
        }

        // 🔹 Dados da pessoa
        $fullName   = trim($data["full_name"] ?? "");
        $document   = preg_replace("/\D/", "", $data["document"] ?? "");
        $birthDate  = !empty($data["birth_date"]) ? $data["birth_date"] : null;

        // 🔹 Dados do funcionário
        $role       = $data["role"] ?? "support";
        $roleName   = trim($data["role_name"] ?? "");
        $hireDate   = $data["hire_date"] ?? date("Y-m-d");
        $status     = $data["status"] ?? "active";

        // 🔹 Verifica duplicidade de documento
        $docExists = (new \Source\Models\Person())
            ->find("document = :d AND id != :id", "d={$document}&id=" . ($person->id ?? 0))
            ->count();

        if ($docExists > 0) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("O documento informado já está cadastrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Salva pessoa
        // $person->full_name  = $fullName;
        // $person->document   = $document;
        // $person->birth_date = $birthDate;

        // if (!$person->save()) {
        //     $json["message"] = $person->message()->toast()->render();
        //     echo json_encode($json);
        //     return;
        // }

        // 🔹 Salva funcionário
        $employee->person_id = $person->id;
        $employee->role       = $role;
        $employee->role_name  = $roleName;
        $employee->hire_date  = $hireDate;
        $employee->status     = $status;

        if (!$employee->save()) {
            $json["message"] = $employee->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        $json["message"] = (new \Source\Support\Message())
            ->success("Funcionário " . (!empty($data["person_id"]) ? "atualizado" : "cadastrado") . " com sucesso!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/funcionarios");
        echo json_encode($json);
    }

    public function deleteEmployee(?array $data): void
    {
        $id = (int)($data["id"] ?? 0);

        if (!$id) {
            (new \Source\Support\Message())->error("ID inválido.")->flash();
            redirect("/app/funcionarios");
            return;
        }

        $employee = (new \Source\Models\App\Employee())->findById($id);
        if (!$employee) {
            (new \Source\Support\Message())->error("Funcionário não encontrado.")->flash();
            redirect("/app/funcionarios");
            return;
        }

        $employee->destroy();

        (new \Source\Support\Message())
            ->success("Funcionário excluído com sucesso!")
            ->flash();

        redirect("/app/funcionarios");
    }

    /**
     * Página para associar pessoa existente a um funcionário
     */
    public function employeeAssign(?array $data): void
    {
        // Busca todas as pessoas que ainda não são funcionários
        $persons = (new \Source\Models\Person())
            ->find("id NOT IN (SELECT person_id FROM employee)", "", "id, full_name, document")
            ->fetch(true);

        $this->renderPage("employees/assign", [
            "title"      => "Associar Pessoa a Funcionário",
            "subtitle"   => "Selecione uma pessoa existente e defina as informações do vínculo como funcionário",
            "persons"    => $persons,
            "activeMenu" => "admin",
            "activesubmenu" => "admfuncionarios",
        ]);
    }

    /**
     * POST para salvar associação pessoa → funcionário
     */
    public function saveEmployeeAssignPost(?array $data): void
    {
        $json = [];

        $personId  = (int)($data["person_id"] ?? 0);
        $role      = $data["role"] ?? "support";
        $roleName  = trim($data["role_name"] ?? "");
        $hireDate  = $data["hire_date"] ?? date("Y-m-d");
        $status    = $data["status"] ?? "active";

        // Verifica se a pessoa existe
        $person = (new \Source\Models\Person())->findById($personId);
        if (!$person) {
            $json["message"] = (new \Source\Support\Message())
                ->error("Pessoa não encontrada.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // Verifica se já é funcionário
        $exists = (new \Source\Models\App\Employee())->findById($personId);
        if ($exists) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("Essa pessoa já está registrada como funcionário.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // Cria vínculo
        $employee = new \Source\Models\App\Employee();
        $employee->person_id = $personId;
        $employee->role      = $role;
        $employee->role_name = $roleName;
        $employee->hire_date = $hireDate;
        $employee->status    = $status;

        if (!$employee->save()) {
            var_dump($employee);
            $json["message"] = $employee->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        $json["message"] = (new \Source\Support\Message())
            ->success("Funcionário associado com sucesso!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/funcionarios");
        echo json_encode($json);
    }


    // Usuários
    public function users(?array $data): void
    {
        $session = new \Source\Core\Session();

        // 🔹 1. Se for POST: salva busca e redireciona
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $search = trim($data["search"] ?? "");

            if ($search !== "") {
                $session->set("user_search", $search);
            } else {
                $session->unset("user_search");
            }

            echo json_encode(["redirect" => url("/app/usuarios")]);
            return;
        }

        // 🔹 2. Se vier GET com ?clear=1, limpa a busca
        if (!empty($_GET["clear"])) {
            $session->unset("user_search");
        }

        // 🔹 3. Busca persistente (mantida na sessão)
        $search = $session->has("user_search") ? $session->user_search : "";

        // 🔹 4. Paginação e limite
        $page  = (int)($data["page"] ?? 1);
        $limit = (int)($data["limit"] ?? 10);

        // 🔹 5. Query
        $accountModel = new \Source\Models\Account();

        if (!empty($search)) {
            $query = $accountModel->find(
                "(email LIKE CONCAT('%', :search, '%')
              OR person_id IN (
                  SELECT id FROM person
                  WHERE full_name LIKE CONCAT('%', :search, '%') /*!999999 NO_INDEX_MERGE */
              ))",
                "search={$search}"
            );
        } else {
            $query = $accountModel->find();
        }

        $total = $query->count();
        $accounts = $query->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
        $pages = ceil($total / $limit);

        $this->renderPage("users/main", [
            "title"    => "Usuários",
            "accounts" => $accounts,
            "search"   => $search,
            "page"     => $page,
            "pages"    => $pages,
            "limit"    => $limit,
            "total"    => $total,
            "activeMenu" => "sistema"
        ]);
    }

    public function user(?array $data): void
    {
        $isEdit = false;

        $user = new \Source\Models\Account();
        $person = new \Source\Models\Person();
        $user->person = $person;

        // 🔹 Edição
        if (!empty($data["id"])) {
            /** @var \Source\Models\Account|null $user */
            $user = (new \Source\Models\Account())->findById((int)$data["id"]);
            if (!$user) {
                (new \Source\Support\Message())->error("Usuário não encontrado.")->flash();
                redirect("/app/usuarios");
                return;
            }

            $isEdit = true;
            $person = $user->person();
            $user->person = $person;
        }

        $this->renderPage("users/form", [
            "title"    => $isEdit ? "Editar Usuário" : "Novo Usuário",
            "subtitle" => $isEdit ? "Atualize as informações do usuário" : "Cadastre um novo usuário",
            "user"     => $user,
            "isEdit"   => $isEdit,
            "activeMenu" => "sistema"
        ]);
    }

    public function saveUserPost(?array $data): void
    {
        $json = [];

        $account = null;
        $person = null;

        // 🔹 Edição
        if (!empty($data["id"])) {
            /** @var \Source\Models\Account|null $account */
            $account = (new \Source\Models\Account())->findById($data["id"]);
            if (!$account) {
                $json["message"] = (new \Source\Support\Message())
                    ->error("Usuário não encontrado.")
                    ->toast()
                    ->render();
                echo json_encode($json);
                return;
            }
            $person = $account->person();
        } else {
            // 🔹 Criação
            $person = new \Source\Models\Person();
            $account = new \Source\Models\Account();
        }

        // 🔹 Limpa e prepara dados
        $fullName   = trim($data["full_name"]);
        $document   = preg_replace("/\D/", "", $data["document"]);
        $personType = $data["person_type"] ?? "individual";
        $birthDate  = !empty($data["birth_date"]) ? $data["birth_date"] : null;
        $email      = trim($data["email"]);

        // 🔹 Verifica duplicidade de CPF
        $cpfExists = (new \Source\Models\Person())
            ->find("document = :d AND id != :id", "d={$document}&id=" . ($person->id ?? 0))
            ->count();

        if ($cpfExists > 0) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("O CPF informado já está cadastrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Verifica duplicidade de e-mail
        $emailExists = (new \Source\Models\Account())
            ->find("email = :e AND id != :id", "e={$email}&id=" . ($account->id ?? 0))
            ->count();

        if ($emailExists > 0) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("O e-mail informado já está cadastrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Atualiza / cria Person
        $person->full_name   = $fullName;
        $person->document    = $document;
        $person->person_type = $personType;
        $person->birth_date  = $birthDate;

        if (!$person->save()) {
            $json["message"] = $person->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Atualiza / cria Account
        $account->person_id = $person->id;
        $account->email     = $email;
        $account->status    = "confirmed";

        if (!empty($data["password"])) {
            $account->password = $data["password"];
        }

        if (!$account->save()) {
            $json["message"] = $account->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        // 🔹 Retorno final
        $json["message"] = (new \Source\Support\Message())
            ->success("Usuário " . (!empty($data["id"]) ? "atualizado" : "criado") . " com sucesso!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/usuarios");
        echo json_encode($json);
    }

    // Perfil
    public function profile(): void
    {
        $this->renderPage("profile/main", [
            "active"      => "profile",
            "title"       => "Perfil",
            "subtitle"    => "Gerencie seu perfil",
            "user"        => $this->user,
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

    // Clientes
    public function customers(?array $data): void
    {
        $session = new \Source\Core\Session();

        // 🔹 1. Se for POST: salva busca e redireciona
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $search = trim($data["search"] ?? "");

            if ($search !== "") {
                $session->set("customer_search", $search);
            } else {
                $session->unset("customer_search");
            }

            echo json_encode(["redirect" => url("/app/clientes")]);
            return;
        }

        // 🔹 2. Se vier GET com ?clear=1, limpa busca
        if (!empty($_GET["clear"])) {
            $session->unset("customer_search");
        }

        // 🔹 3. Recupera busca persistente
        $search = $session->has("customer_search") ? $session->customer_search : "";

        // 🔹 4. Paginação
        $page  = (int)($data["page"] ?? 1);
        $limit = (int)($data["limit"] ?? 10);

        // 🔹 5. Query base
        $customerModel = new \Source\Models\App\Customer();

        if (!empty($search)) {
            $query = $customerModel->find(
                "person_id IN (
                SELECT id FROM person
                WHERE full_name LIKE CONCAT('%', :search, '%')
                OR document LIKE CONCAT('%', :search, '%')
                /*!999999 NO_INDEX_MERGE */
            )",
                "search={$search}"
            );
        } else {
            $query = $customerModel->find();
        }

        $total = $query->count();
        $customers = $query->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
        $pages = ceil($total / $limit);

        $this->renderPage("customers/main", [
            "title"    => "Clientes",
            "customers" => $customers,
            "search"   => $search,
            "page"     => $page,
            "pages"    => $pages,
            "limit"    => $limit,
            "total"    => $total,
            "activeMenu" => "admin",
            "activesubmenu" => "admclientes",
        ]);
    }

    public function searchClientByCpf(?array $data): void
    {
        $json = [];

        // 🔹 1. Validação básica
        $document = $data["document"] ?? null;
        if (empty($document)) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("Informe o CPF ou CNPJ para busca.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 2. Normaliza documento (mantém apenas números)
        $document = preg_replace("/\D/", "", $document);

        // 🔹 3. Busca a pessoa (Person)
        $person = (new \Source\Models\Person())
            ->find("document = :d", "d={$document}")
            ->fetch();

        if (!$person) {
            $json["found"] = false;
            $json["message"] = (new \Source\Support\Message())
                ->info("Pessoa não encontrada. Você pode criar um novo usuário.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // 🔹 4. Busca a conta (Account)
        $account = (new \Source\Models\Account())
            ->find("person_id = :pid", "pid={$person->id}")
            ->fetch();

        // 🔹 5. Busca o cliente (Customer)
        $customer = (new \Source\Models\App\Customer())
            ->find("person_id = :pid", "pid={$person->id}")
            ->fetch();

        // 🔹 6. Busca o contrato ativo (plano)
        $contract = (new \Source\Models\App\Contract())
            ->find("customer_id = :cid AND status = 'active'", "cid={$person->id}")
            ->order("id DESC")
            ->fetch();

        $plan = null;
        if ($contract && !empty($contract->plan_id)) {
            $plan = (new \Source\Models\App\Plan())
                ->findById((int)$contract->plan_id);
        }

        // 🔹 7. Busca o equipamento ativo vinculado à pessoa
        $activeEquipmentLink = (new \Source\Models\App\CustomerEquipment())
            ->find("customer_id = :cid AND (end_date IS NULL OR end_date = '0000-00-00')", "cid={$person->id}")
            ->order("id DESC")
            ->fetch();

        $activeEquipment = null;

        if ($activeEquipmentLink) {
            $eq = (new \Source\Models\App\Equipment())->findById($activeEquipmentLink->equipment_id);
            if ($eq) {
                $activeEquipment = (object) [
                    "equipment_id"   => $eq->id,
                    "equipment_name" => trim(implode(" - ", array_filter([
                        $eq->type,
                        $eq->manufacturer,
                        $eq->model
                    ]))),
                    "start_date"     => $activeEquipmentLink->start_date,
                    "end_date"       => $activeEquipmentLink->end_date
                ];
            }
        }

        // 🔹 Também busca todos os equipamentos (para preencher o select)
        $equipments = (new \Source\Models\App\Equipment())
            ->find()
            ->order("type ASC")
            ->fetch(true);


        // 🔹 8. Monta resposta JSON
        $json["found"] = true;
        $json["person"] = [
            "id"          => $person->id,
            "full_name"   => $person->full_name,
            "document"    => $person->document,
            "person_type" => $person->person_type,
            "birth_date"  => $person->birth_date
        ];

        $json["account"] = $account ? [
            "id"     => $account->id,
            "email"  => $account->email,
            "status" => $account->status ?? null
        ] : null;

        $json["customer"] = $customer ? [
            "id"      => $customer->id ?? null,
            "status"  => $customer->status ?? null,
            "plan_id" => $contract->plan_id ?? null,
            "plan"    => $plan ? $plan->name : null
        ] : null;

        $json["equipments"] = $equipments ?: [];
        $json["active_equipment"] = $activeEquipment ?: null;

        echo json_encode($json);
    }

    public function searchClientById(?array $data): void
    {
        $personId = $data["person_id"] ?? null;

        if (!$personId) {
            echo json_encode(["found" => false]);
            return;
        }

        // Reaproveita a MESMA lógica existente
        $person = (new \Source\Models\Person())->findById($personId);

        if (!$person) {
            echo json_encode(["found" => false]);
            return;
        }

        // 🔹 4. Busca a conta (Account)
        $account = (new \Source\Models\Account())
            ->find("person_id = :pid", "pid={$person->id}")
            ->fetch();

        // 🔹 5. Busca o cliente (Customer)
        $customer = (new \Source\Models\App\Customer())
            ->find("person_id = :pid", "pid={$person->id}")
            ->fetch();

        // 🔹 6. Busca o contrato ativo (plano)
        $contract = (new \Source\Models\App\Contract())
            ->find("customer_id = :cid AND status = 'active'", "cid={$person->id}")
            ->order("id DESC")
            ->fetch();

        $plan = null;
        if ($contract && !empty($contract->plan_id)) {
            $plan = (new \Source\Models\App\Plan())
                ->findById((int)$contract->plan_id);
        }

        // 🔹 7. Busca o equipamento ativo vinculado à pessoa
        $activeEquipmentLink = (new \Source\Models\App\CustomerEquipment())
            ->find("customer_id = :cid AND (end_date IS NULL OR end_date = '0000-00-00')", "cid={$person->id}")
            ->order("id DESC")
            ->fetch();

        $activeEquipment = null;

        if ($activeEquipmentLink) {
            $eq = (new \Source\Models\App\Equipment())->findById($activeEquipmentLink->equipment_id);
            if ($eq) {
                $activeEquipment = (object) [
                    "equipment_id"   => $eq->id,
                    "equipment_name" => trim(implode(" - ", array_filter([
                        $eq->type,
                        $eq->manufacturer,
                        $eq->model
                    ]))),
                    "start_date"     => $activeEquipmentLink->start_date,
                    "end_date"       => $activeEquipmentLink->end_date
                ];
            }
        }

        // 🔹 Também busca todos os equipamentos (para preencher o select)
        $equipments = (new \Source\Models\App\Equipment())
            ->find()
            ->order("type ASC")
            ->fetch(true);

        echo json_encode([
            "found"           => true,
            "person"          => $person,
            "account"         => $account,
            "customer"        => $customer,
            "equipments"      => $equipments,
            "active_equipment" => $activeEquipment,
        ]);
    }



    public function clientForm(?array $data): void
    {
        $personId = $data["id"] ?? null;

        // 🔹 1. Carrega planos e equipamentos disponíveis
        $plans = (new \Source\Models\App\Plan())
            ->find()
            ->order("price ASC")
            ->fetch(true);

        $equipments = (new \Source\Models\App\Equipment())
            ->find()
            ->order("type ASC")
            ->fetch(true);

        // 🔹 2. Se foi passado um ID (edição)
        $person = null;
        $account = null;
        $customer = null;
        $activePlan = null;
        $activeEquipment = null;

        if ($personId) {
            // Pessoa
            $person = (new \Source\Models\Person())->findById($personId);

            // Conta
            $account = (new \Source\Models\Account())
                ->find("person_id = :pid", "pid={$personId}")
                ->fetch();

            // Cliente
            $customer = (new \Source\Models\App\Customer())
                ->find("person_id = :pid", "pid={$personId}")
                ->fetch();

            // Plano ativo
            $contract = (new \Source\Models\App\Contract())
                ->find("customer_id = :cid AND status = 'active'", "cid={$personId}")
                ->fetch();

            if ($contract && $contract->plan_id) {
                $activePlan = (new \Source\Models\App\Plan())->findById($contract->plan_id);
            }

            // Equipamento ativo
            $equipmentLink = (new \Source\Models\App\CustomerEquipment())
                ->find("customer_id = :cid AND (end_date IS NULL OR end_date = '0000-00-00')", "cid={$personId}")
                ->fetch();

            if ($equipmentLink) {
                $eq = (new \Source\Models\App\Equipment())->findById($equipmentLink->equipment_id);
                if ($eq) {
                    $activeEquipment = $eq;
                }
            }
        }

        $people = (new \Source\Models\Person())
            ->find()
            ->order("full_name ASC")
            ->fetch(true);

        // 🔹 3. Renderiza a página
        $this->renderPage("customers/form", [
            "title"          => $personId ? "Editar Cliente" : "Associar Cliente",
            "subtitle"       => $personId ? "Atualize o plano e equipamento" : "Associe um cliente a um plano e equipamento",
            "plans"          => $plans,
            "equipments"     => $equipments,
            "person"         => $person,
            "account"        => $account,
            "customer"       => $customer,
            "activePlan"     => $activePlan,
            "activeEquipment" => $activeEquipment,
            "activeMenu"     => "admin",
            "activesubmenu"     => "admclientes",
            "people"            => $people
        ]);
    }

    public function cancelPlanPost(?array $data): void
    {
        $json = [];

        $personId = (int)($data["person_id"] ?? 0);
        if (!$personId) {
            $json["message"] = "ID do cliente não informado.";
            echo json_encode($json);
            return;
        }

        // 🔹 1. Cancela contrato ativo
        $contract = (new \Source\Models\App\Contract())
            ->find("customer_id = :cid AND status = 'active'", "cid={$personId}")
            ->fetch();

        if (!$contract) {
            $json["message"] = "Nenhum contrato ativo encontrado.";
            echo json_encode($json);
            return;
        }

        $contract->status = "canceled";
        $contract->end_date = date("Y-m-d");
        $contract->save();

        // 🔹 2. Cancela automaticamente o equipamento associado
        $equipmentLink = (new \Source\Models\App\CustomerEquipment())
            ->find("customer_id = :cid AND (end_date IS NULL OR end_date = '0000-00-00')", "cid={$personId}")
            ->fetch();

        if ($equipmentLink) {
            $equipmentLink->end_date = date("Y-m-d");
            $equipmentLink->save();

            // Libera o equipamento
            $eq = (new \Source\Models\App\Equipment())->findById($equipmentLink->equipment_id);
            if ($eq) {
                $eq->status = "available";
                $eq->save();
            }
        }

        $json["success"] = true;
        $json["message"] = "Plano e equipamento cancelados com sucesso!";

        echo json_encode($json);
    }




    public function customerSave(array $data): void
    {
        $personId = filter_var($data["person_id"] ?? null, FILTER_VALIDATE_INT);
        $planId = filter_var($data["plan_id"] ?? null, FILTER_VALIDATE_INT);
        $equipmentId = filter_var($data["equipment_id"] ?? null, FILTER_VALIDATE_INT);

        if (!$personId || !$planId || !$equipmentId) {
            $this->message->error("Selecione o cliente, o plano e o equipamento.")->flash();
            redirect("/app/clientes");
            return;
        }

        // 1️⃣ Garante o cliente ativo
        $customer = (new Customer())->find("person_id = :pid", "pid={$personId}")->fetch();
        if (!$customer) {
            $customer = new Customer();
            $customer->person_id = $personId;
            $customer->status = "active";
            $customer->save();
        }

        // 2️⃣ Cancela contratos ativos anteriores
        $activeContracts = (new \Source\Models\App\Contract())
            ->find("customer_id = :cid AND status = 'active'", "cid={$personId}")
            ->fetch(true);

        if ($activeContracts) {
            foreach ($activeContracts as $ct) {
                $ct->status = "canceled";
                $ct->end_date = date("Y-m-d");
                $ct->save();
            }
        }

        // 3️⃣ Cancela equipamentos ativos anteriores
        $activeEquipments = (new \Source\Models\App\CustomerEquipment())
            ->find("customer_id = :cid AND (end_date IS NULL OR end_date = '0000-00-00')", "cid={$personId}")
            ->fetch(true);

        if ($activeEquipments) {
            foreach ($activeEquipments as $ce) {
                $ce->end_date = date("Y-m-d");
                $ce->save();

                $oldEq = (new \Source\Models\App\Equipment())->findById((int)$ce->equipment_id);
                if ($oldEq) {
                    $oldEq->status = "available";
                    $oldEq->save();
                }
            }
        }

        // 4️⃣ Cria novo contrato (ligado à pessoa)
        $contract = new \Source\Models\App\Contract();
        $contract->customer_id = $personId;
        $contract->plan_id = $planId;
        $contract->start_date = date("Y-m-d");
        $contract->status = "active";
        $contract->save();

        // 5️⃣ Cria vínculo de equipamento (ligado à pessoa)
        $customerEquipment = new \Source\Models\App\CustomerEquipment();
        $customerEquipment->customer_id = $personId;
        $customerEquipment->equipment_id = $equipmentId;
        $customerEquipment->start_date = date("Y-m-d");
        $customerEquipment->save();

        // Atualiza status do novo equipamento
        $equipment = (new \Source\Models\App\Equipment())->findById($equipmentId);
        if ($equipment) {
            $equipment->status = "allocated";
            $equipment->save();
        }

        $this->message->success("Cliente associado com sucesso!")->flash();
        $json["redirect"] = url("/app/clientes");
        jsonResponse($json);
    }

    public function myPlan(): void
    {
        $user = \Source\Models\Auth::account();

        // Busca o cliente vinculado à pessoa logada
        $customer = (new \Source\Models\App\Customer())
            ->find("person_id = :pid", "pid={$user->id}")
            ->fetch();

        if (!$customer) {
            $this->message->warning("Nenhum plano associado a este usuário.")->flash();
            redirect("/app");
            return;
        }

        // Busca o contrato ativo ou último contrato
        $contract = (new \Source\Models\App\Contract())
            ->find("customer_id = :cid", "cid={$customer->person_id}")
            ->order("id DESC")
            ->fetch();

        // Busca o plano associado ao contrato
        $plan = $contract ? (new \Source\Models\App\Plan())->findById($contract->plan_id) : null;

        // Busca o equipamento associado
        $equipmentLink = (new \Source\Models\App\CustomerEquipment())
            ->find("customer_id = :cid AND end_date IS NULL", "cid={$customer->person_id}")
            ->fetch();

        $equipment = $equipmentLink
            ? (new \Source\Models\App\Equipment())->findById($equipmentLink->equipment_id)
            : null;

        // Renderiza view
        $this->renderPage("my-plan", [
            "title" => "Detalhes do Meu Plano",
            "activeMenu" => "cliente",
            "activesubmenu" => "meuplano",
            "plan" => $plan,
            "equipment" => $equipment
        ]);
    }

    public function serverStatus(): void
    {
        $this->renderPage("service-status", [
            "activeMenu" => "cliente",
            "activesubmenu" => "statusserver"
        ]);
    }


    public function customerPlan(): void
    {
        $user = Auth::account();

        // 1️⃣ Busca o registro do cliente vinculado à pessoa
        $customer = (new \Source\Models\App\Customer())
            ->find("person_id = :pid", "pid={$user->id}")
            ->fetch();

        if (!$customer) {
            $this->message->warning("Nenhum plano ativo encontrado para seu usuário.")->flash();
            redirect("/app");
            return;
        }

        // 2️⃣ Busca o contrato ativo
        $contract = (new \Source\Models\App\Contract())
            ->find("customer_id = :cid AND status = 'active'", "cid={$customer->id}")
            ->fetch();

        // 3️⃣ Busca o plano vinculado
        $plan = null;
        if ($contract) {
            $plan = (new \Source\Models\App\Plan())->findById($contract->plan_id);
        }

        // 4️⃣ Busca equipamento vinculado (opcional)
        $equipment = (new \Source\Models\App\CustomerEquipment())
            ->find("customer_id = :cid AND end_date IS NULL", "cid={$customer->id}")
            ->fetch();

        if ($equipment) {
            $equipment = (new \Source\Models\App\Equipment())->findById($equipment->equipment_id);
        }

        // 5️⃣ Renderiza view
        $this->renderPage("customer-plan", [
            "title" => "Meu Plano",
            "customer" => $customer,
            "contract" => $contract,
            "plan" => $plan,
            "equipment" => $equipment
        ]);
    }






    // public function saveCustomer(?array $data): void
    // {
    //     $json = [];

    //     // Esperamos: document, person_id (opcional), plan_id (opcional), equipments => array of equipment_id, start_date, end_date
    //     $document = preg_replace("/\D/", "", $data["document"] ?? "");
    //     $personId = !empty($data["person_id"]) ? (int)$data["person_id"] : null;
    //     $planId   = !empty($data["plan_id"]) ? (int)$data["plan_id"] : null;
    //     $equipments = $data["equipments"] ?? []; // esperar array [[equipment_id, start_date, end_date], ...]

    //     // Verifica pessoa
    //     if ($personId) {
    //         $person = (new \Source\Models\Person())->findById($personId);
    //         if (!$person) {
    //             $json["message"] = (new \Source\Support\Message())->error("Pessoa não encontrada.")->toast()->render();
    //             echo json_encode($json);
    //             return;
    //         }
    //     } else {
    //         // tenta achar por document
    //         $person = (new \Source\Models\Person())->find("document = :d", "d={$document}")->fetch();
    //         if (!$person) {
    //             $json["message"] = (new \Source\Support\Message())->warning("Pessoa não encontrada. Crie a pessoa antes.")->toast()->render();
    //             echo json_encode($json);
    //             return;
    //         }
    //     }

    //     // Se já existe customer?
    //     $customerModel = new \Source\Models\App\Customer();
    //     $customer = $customerModel->find("person_id = :pid", "pid={$person->id}")->fetch();

    //     if (!$customer) {
    //         // cria novo customer
    //         $customer = new \Source\Models\App\Customer();
    //         $customer->person_id = $person->id;
    //     }

    //     // atualiza campos do customer (por ex. plan_id, status)
    //     if (!is_null($planId)) {
    //         $customer->plan_id = $planId;
    //     }
    //     $customer->status = $data["customer_status"] ?? ($customer->status ?? 'active');

    //     if (!$customer->save()) {
    //         $json["message"] = $customer->message()->toast()->render();
    //         echo json_encode($json);
    //         return;
    //     }

    //     // Agora alocar equipamentos: para simplicidade, removo/insiro
    //     // Você pode optar por inserir novos sem deletar. Exemplo abaixo apaga todas as alocações e recria.
    //     $pdo = \Source\Core\Connect::getInstance();
    //     $pdo->beginTransaction();
    //     try {
    //         // opcional: remover alocações antigas (se quiser sobrescrever)
    //         $stmtDel = $pdo->prepare("DELETE FROM customer_equipment WHERE customer_id = :cid");
    //         $stmtDel->execute(["cid" => $person->id]); // cuidado: a constraint customer_equipment_ibfk_1 usa customer_id referencing customer.person_id
    //         // Inserir novas alocações
    //         $stmtIns = $pdo->prepare("INSERT INTO customer_equipment (customer_id, equipment_id, start_date, end_date) VALUES (:cid, :eid, :s, :e)");
    //         foreach ($equipments as $eq) {
    //             $eid = (int)$eq["equipment_id"];
    //             $s = !empty($eq["start_date"]) ? $eq["start_date"] : date("Y-m-d");
    //             $e = !empty($eq["end_date"]) ? $eq["end_date"] : null;
    //             $stmtIns->execute([
    //                 "cid" => $person->id,
    //                 "eid" => $eid,
    //                 "s" => $s,
    //                 "e" => $e
    //             ]);
    //         }
    //         $pdo->commit();
    //     } catch (\Throwable $th) {
    //         $pdo->rollBack();
    //         $json["message"] = (new \Source\Support\Message())->error("Falha ao alocar equipamentos: " . $th->getMessage())->toast()->render();
    //         echo json_encode($json);
    //         return;
    //     }

    //     $json["message"] = (new \Source\Support\Message())->success("Cliente atualizado com sucesso")->toast()->render();
    //     $json["redirect"] = url("/clientes"); // ou a rota que quiser
    //     echo json_encode($json);
    // }


    // Planos
    public function plans(?array $data = null): void
    {
        $page  = isset($data["page"]) ? (int)$data["page"] : 1;
        $limit = isset($data["limit"]) ? (int)$data["limit"] : 10;
        $search = $data["search"] ?? null;

        $plan = new Plan();

        if ($search) {
            $plans = $plan->find("name LIKE :s", "s=%{$search}%")->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
            $total = $plan->find("name LIKE :s", "s=%{$search}%")->count();
        } else {
            $plans = $plan->find()->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
            $total = $plan->find()->count();
        }

        $pages = ceil($total / $limit);

        $this->renderPage("plans/main", [
            "plans"  => $plans,
            "page"   => $page,
            "pages"  => $pages,
            "limit"  => $limit,
            "search" => $search,
            "total"  => $total,
            "active"      => "plans",
            "title"       => "Planos",
            "subtitle"    => "Gerencie seus planos",
            "activeMenu"  => "sistema"
        ]);
    }

    public function planForm(?array $data): void
    {
        $isEdit = false;
        $plan = null;

        // Edição
        if (!empty($data["id"])) {
            $plan = (new Plan())->findById((int)$data["id"]);
            if (!$plan) {
                $this->message->warning("Plano não encontrado.")->toast()->flash();
                redirect("/app/planos");
                return;
            }
            $isEdit = true;
        } else {
            // Criação
            $plan = new Plan();
        }

        $this->renderPage("plans/form", [
            "title"    => $isEdit ? "Editar Plano" : "Novo Plano",
            "subtitle" => $isEdit ? "Atualize as informações do plano" : "Cadastre um novo plano",
            "plan"     => $plan,
            "isEdit"   => $isEdit,
            "activeMenu" => "sistema"
        ]);
    }

    public function savePlan(array $data): void
    {
        if (!empty($data["id"])) {
            $plan = (new Plan())->findById((int)$data["id"]);
            if (!$plan) {
                $this->message->error("Plano não encontrado.")->toast()->flash();
                $json["redirect"] = url("/app/planos");
                echo json_encode($json);
                return;
            }
        } else {
            $plan = new Plan();
        }

        if (!empty($data)) {
            $plan->bootstrap(
                $data["name"],
                (int)$data["download_speed"],
                (int)$data["upload_speed"],
                (float)$data["price"],
                !empty($data["data_cap"]) ? (int)$data["data_cap"] : null,
                $data["description"] ?? null
            );

            if (!$plan->save()) {
                $this->message->error("Erro ao salvar o plano: " . $plan->message()->getText())->toast()->render();
                return;
            }

            $this->message->success("Plano salvo com sucesso!")->toast()->flash();
            $json["message"] = $this->message->render();
            $json["redirect"] = url("/app/planos");
            echo json_encode($json);
        }
    }

    // Chamados (Support Tickets)
    public function tickets(?array $data): void
    {
        $session = new \Source\Core\Session();

        // POST: salva busca e redireciona
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $search = trim($data["search"] ?? "");

            if ($search !== "") {
                $session->set("ticket_search", $search);
            } else {
                $session->unset("ticket_search");
            }

            echo json_encode(["redirect" => url("/app/chamados")]);
            return;
        }

        // Limpa busca
        if (!empty($_GET["clear"])) {
            $session->unset("ticket_search");
        }

        // Busca persistente
        $search = $session->has("ticket_search") ? $session->ticket_search : "";

        // Filtros
        $filterStatus = $data["status"] ?? null;
        $filterCategory = $data["category"] ?? null;
        $filterPriority = $data["priority"] ?? null;

        // Paginação
        $page  = (int)($data["page"] ?? 1);
        $limit = (int)($data["limit"] ?? 10);

        // Query - APENAS CHAMADOS EM ABERTO (não resolvidos e não cancelados)
        $ticketModel = new SupportTicket();
        $conditions = ["status NOT IN ('resolved', 'canceled')"];
        $params = [];

        if (!empty($search)) {
            $conditions[] = "(id = :search OR description LIKE CONCAT('%', :search2, '%') OR customer_id IN (SELECT person_id FROM person WHERE full_name LIKE CONCAT('%', :search3, '%')))";
            $params["search"] = $search;
            $params["search2"] = $search;
            $params["search3"] = $search;
        }

        if ($filterStatus) {
            $conditions[] = "status = :status";
            $params["status"] = $filterStatus;
        }

        if ($filterCategory) {
            $conditions[] = "category = :category";
            $params["category"] = $filterCategory;
        }

        if ($filterPriority) {
            $conditions[] = "priority = :priority";
            $params["priority"] = $filterPriority;
        }

        $where = implode(" AND ", $conditions);
        $query = $ticketModel->find($where, http_build_query($params));

        $total = $query->count();
        $tickets = $query->order("opened_at DESC")->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
        $pages = ceil($total / $limit);

        $this->renderPage("tickets/main", [
            "title"          => "Chamados Em Aberto",
            "tickets"        => $tickets,
            "search"         => $search,
            "filterStatus"   => $filterStatus,
            "filterCategory" => $filterCategory,
            "filterPriority" => $filterPriority,
            "page"           => $page,
            "pages"          => $pages,
            "limit"          => $limit,
            "total"          => $total,
            "activeMenu"     => "admin",
            "activesubmenu"     => "admchamados",
        ]);
    }

    public function ticketsHistory(?array $data): void
    {
        $session = new \Source\Core\Session();

        // POST: salva busca e redireciona
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $search = trim($data["search"] ?? "");

            if ($search !== "") {
                $session->set("ticket_history_search", $search);
            } else {
                $session->unset("ticket_history_search");
            }

            echo json_encode(["redirect" => url("/app/chamados/historico")]);
            return;
        }

        // Limpa busca
        if (!empty($_GET["clear"])) {
            $session->unset("ticket_history_search");
        }

        // Busca persistente
        $search = $session->has("ticket_history_search") ? $session->ticket_history_search : "";

        // Filtros
        $filterStatus = $data["status"] ?? null;
        $filterCategory = $data["category"] ?? null;
        $filterPriority = $data["priority"] ?? null;

        // Paginação
        $page  = (int)($data["page"] ?? 1);
        $limit = (int)($data["limit"] ?? 10);

        // Query - APENAS CHAMADOS RESOLVIDOS OU CANCELADOS
        $ticketModel = new SupportTicket();
        $conditions = ["status IN ('resolved', 'canceled')"];
        $params = [];

        error_log("=== TICKETS HISTORY DEBUG ===");
        error_log("Initial conditions: " . print_r($conditions, true));

        if (!empty($search)) {
            $conditions[] = "(id = :search OR description LIKE CONCAT('%', :search2, '%') OR customer_id IN (SELECT person_id FROM person WHERE full_name LIKE CONCAT('%', :search3, '%')))";
            $params["search"] = $search;
            $params["search2"] = $search;
            $params["search3"] = $search;
        }

        if ($filterStatus) {
            $conditions[] = "status = :status";
            $params["status"] = $filterStatus;
        }

        if ($filterCategory) {
            $conditions[] = "category = :category";
            $params["category"] = $filterCategory;
        }

        if ($filterPriority) {
            $conditions[] = "priority = :priority";
            $params["priority"] = $filterPriority;
        }

        $where = implode(" AND ", $conditions);
        $query = $ticketModel->find($where, http_build_query($params));

        error_log("WHERE clause: " . $where);
        error_log("Params: " . print_r($params, true));

        $total = $query->count();
        error_log("Total found: " . $total);

        $tickets = $query->order("closed_at DESC, opened_at DESC")->limit($limit)->offset(($page - 1) * $limit)->fetch(true);
        error_log("Tickets returned: " . count($tickets ?: []));
        $pages = ceil($total / $limit);

        $this->renderPage("tickets/history", [
            "title"          => "Histórico de Chamados",
            "tickets"        => $tickets,
            "search"         => $search,
            "filterStatus"   => $filterStatus,
            "filterCategory" => $filterCategory,
            "filterPriority" => $filterPriority,
            "page"           => $page,
            "pages"          => $pages,
            "limit"          => $limit,
            "total"          => $total,
            "activeMenu"     => "support"
        ]);
    }

    public function ticket(?array $data): void
    {
        $isEdit = false;
        $ticket = new SupportTicket();

        // Edição
        if (!empty($data["id"])) {
            $ticket = (new SupportTicket())->findById((int)$data["id"]);
            if (!$ticket) {
                (new \Source\Support\Message())->error("Chamado não encontrado.")->flash();
                redirect("/app/chamados");
                return;
            }
            $isEdit = true;
        }

        // Busca clientes para o select
        $customers = (new \Source\Models\App\Customer())
            ->find(null, null, "person_id")
            ->fetch(true);

        // Busca funcionários para o select
        $employees = (new \Source\Models\App\Employee())
            ->find("status = 'active'", null, "person_id")
            ->fetch(true);

        // Detecta de onde veio (histórico ou chamados em aberto)
        $backUrl = url("/app/chamados"); // Padrão: chamados em aberto
        if (isset($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
            if (strpos($referer, '/chamados/historico') !== false) {
                $backUrl = url("/app/chamados/historico");
            }
        }

        $this->renderPage("tickets/form", [
            "title"       => $isEdit ? "Editar Chamado" : "Novo Chamado",
            "subtitle"    => $isEdit ? "Atualize as informações do chamado" : "Registre um novo chamado",
            "ticket"      => $ticket,
            "customers"   => $customers,
            "employees"   => $employees,
            "isEdit"      => $isEdit,
            "activeMenu"  => "support",
            "backUrl"     => $backUrl
        ]);
    }

    public function saveTicketPost(?array $data): void
    {
        $json = [];

        // Edição ou novo
        if (!empty($data["id"])) {
            $ticket = (new SupportTicket())->findById((int)$data["id"]);
            if (!$ticket) {
                $json["message"] = (new \Source\Support\Message())
                    ->error("Chamado não encontrado.")
                    ->toast()
                    ->render();
                echo json_encode($json);
                return;
            }
        } else {
            $ticket = new SupportTicket();
        }

        // Dados do formulário
        $customerId = (int)($data["customer_id"] ?? 0);
        $employeeId = !empty($data["employee_id"]) ? (int)$data["employee_id"] : null;
        $title = trim($data["title"] ?? "");
        $category = $data["category"] ?? "technical";
        $priority = $data["priority"] ?? "low";
        $description = trim($data["description"] ?? "");
        $status = $data["status"] ?? "open";

        // Validações básicas
        if (!$customerId) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("Selecione um cliente.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        if (empty($title) || mb_strlen($title) > 255) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("O assunto do chamado é obrigatório e deve ter até 255 caracteres.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        if (empty($description) || mb_strlen($description) < 10) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("A descrição do chamado é obrigatória e deve ter pelo menos 10 caracteres.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // Atualiza/cria ticket
        $isNew = empty($ticket->id);

        $ticket->customer_id = $customerId;
        $ticket->employee_id = $employeeId;
        $ticket->title = $title;
        $ticket->category = $category;
        $ticket->priority = $priority;
        $ticket->description = $description;
        $ticket->status = $status;

        // Se foi resolvido ou cancelado, registra closed_at
        if (in_array($status, ["resolved", "canceled"]) && empty($ticket->closed_at)) {
            $ticket->closed_at = date("Y-m-d H:i:s");
        }

        if (!$ticket->save()) {
            $json["message"] = $ticket->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        // Registra no histórico
        if ($isNew) {
            TicketHistory::log(
                (int)$ticket->id,
                "created",
                Auth::account()->id,
                null,
                null,
                null,
                "Chamado criado"
            );
        } else {
            TicketHistory::log(
                (int)$ticket->id,
                "updated",
                Auth::account()->id,
                null,
                null,
                null,
                "Chamado atualizado"
            );
        }

        $json["message"] = (new \Source\Support\Message())
            ->success("Chamado " . ($isNew ? "criado" : "atualizado") . " com sucesso!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/chamados");
        echo json_encode($json);
    }

    public function deleteTicket(?array $data): void
    {
        $id = (int)($data["id"] ?? 0);

        if (!$id) {
            (new \Source\Support\Message())->error("ID inválido.")->flash();
            redirect("/app/chamados");
            return;
        }

        $ticket = (new SupportTicket())->findById($id);
        if (!$ticket) {
            (new \Source\Support\Message())->error("Chamado não encontrado.")->flash();
            redirect("/app/chamados");
            return;
        }

        $ticket->destroy();

        (new \Source\Support\Message())
            ->success("Chamado excluído com sucesso!")
            ->flash();

        redirect("/app/chamados");
    }

    public function assignTicket(?array $data): void
    {
        $json = [];

        $ticketId = (int)($data["id"] ?? 0);
        $employeeId = (int)($data["employee_id"] ?? 0);

        if (!$ticketId || !$employeeId) {
            $json["message"] = (new \Source\Support\Message())
                ->error("Dados inválidos.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        /** @var SupportTicket|null $ticket */
        $ticket = (new SupportTicket())->findById($ticketId);
        if (!$ticket) {
            $json["message"] = (new \Source\Support\Message())
                ->error("Chamado não encontrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        if (!$ticket->assign($employeeId)) {
            $json["message"] = $ticket->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        $json["message"] = (new \Source\Support\Message())
            ->success("Funcionário atribuído ao chamado!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/chamados");
        echo json_encode($json);
    }

    public function updateTicketStatus(?array $data): void
    {
        $json = [];

        $ticketId = (int)($data["id"] ?? 0);
        $status = $data["status"] ?? null;

        if (!$ticketId || !$status) {
            $json["message"] = (new \Source\Support\Message())
                ->error("Dados inválidos.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        /** @var SupportTicket|null $ticket */
        $ticket = (new SupportTicket())->findById($ticketId);
        if (!$ticket) {
            $json["message"] = (new \Source\Support\Message())
                ->error("Chamado não encontrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        // Validar se há funcionário atribuído antes de mudar status
        if (empty($ticket->employee_id)) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("É necessário atribuir um funcionário ao chamado antes de alterar o status.")
                ->toast()
                ->render();
            echo json_encode($json);
            return;
        }

        if (!$ticket->updateStatus($status)) {
            $json["message"] = $ticket->message()->toast()->render();
            echo json_encode($json);
            return;
        }

        $json["message"] = (new \Source\Support\Message())
            ->success("Status do chamado atualizado!")
            ->toast()
            ->render();

        $json["redirect"] = url("/app/chamados");
        echo json_encode($json);
    }

    // Dashboard de Chamados
    public function ticketsDashboard(): void
    {
        $ticketModel = new SupportTicket();

        // Estatísticas gerais
        $totalTickets = $ticketModel->find()->count();
        $openTickets = $ticketModel->find("status = 'open'")->count();
        $inProgressTickets = $ticketModel->find("status = 'in-progress'")->count();
        $resolvedTickets = $ticketModel->find("status = 'resolved'")->count();
        $canceledTickets = $ticketModel->find("status = 'canceled'")->count();

        // Por categoria
        $byCategory = [
            'installation' => $ticketModel->find("category = 'installation'")->count(),
            'maintenance' => $ticketModel->find("category = 'maintenance'")->count(),
            'billing' => $ticketModel->find("category = 'billing'")->count(),
            'cancellation' => $ticketModel->find("category = 'cancellation'")->count(),
            'technical' => $ticketModel->find("category = 'technical'")->count()
        ];

        // Por prioridade
        $byPriority = [
            'low' => $ticketModel->find("priority = 'low'")->count(),
            'medium' => $ticketModel->find("priority = 'medium'")->count(),
            'high' => $ticketModel->find("priority = 'high'")->count(),
            'critical' => $ticketModel->find("priority = 'critical'")->count()
        ];

        // Tickets recentes
        $recentTickets = $ticketModel->find()
            ->order("opened_at DESC")
            ->limit(10)
            ->fetch(true);

        // Tempo médio de resolução (últimos 30 dias)
        $pdo = \Source\Core\Connect::getInstance();
        $stmt = $pdo->query("
            SELECT AVG(TIMESTAMPDIFF(HOUR, opened_at, closed_at)) as avg_hours
            FROM support_ticket
            WHERE status = 'resolved'
            AND closed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $avgResolution = $stmt->fetch(\PDO::FETCH_ASSOC);
        $avgHours = round($avgResolution['avg_hours'] ?? 0, 1);

        $this->renderPage("tickets/dashboard", [
            "title" => "Dashboard de Chamados",
            "subtitle" => "Visão geral e estatísticas",
            "totalTickets" => $totalTickets,
            "openTickets" => $openTickets,
            "inProgressTickets" => $inProgressTickets,
            "resolvedTickets" => $resolvedTickets,
            "canceledTickets" => $canceledTickets,
            "byCategory" => $byCategory,
            "byPriority" => $byPriority,
            "recentTickets" => $recentTickets,
            "avgHours" => $avgHours,
            "activeMenu" => "admin",
            "activesubmenu" => "admchamados",
        ]);
    }

    // Adicionar comentário
    public function addTicketComment(?array $data): void
    {
        // Limpa qualquer output anterior e define header JSON
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');

        error_log("=== addTicketComment called ===");
        error_log("Data received: " . print_r($data, true));
        error_log("POST data: " . print_r($_POST, true));

        $json = ["success" => false];

        // Support both route formats: /chamado/comentario/adicionar and /chamado/{id}/comentario
        $ticketId = (int)($data["ticket_id"] ?? $data["id"] ?? $_POST["ticket_id"] ?? 0);
        $comment = trim($data["comment"] ?? $_POST["comment"] ?? "");
        $isInternal = !empty($data["is_internal"] ?? $_POST["is_internal"] ?? null) ? 1 : 0;
        $context = $data["context"] ?? $_POST["context"] ?? "client"; // "admin" ou "client"

        error_log("Ticket ID: {$ticketId}, Comment length: " . strlen($comment) . ", Is internal: {$isInternal}, Context: {$context}");

        if (!$ticketId || empty($comment)) {
            error_log("ERROR: Invalid data - ticketId: {$ticketId}, comment empty: " . (empty($comment) ? 'yes' : 'no'));
            $json["message"] = "Dados inválidos.";
            echo json_encode($json);
            exit;
        }

        $ticket = (new SupportTicket())->findById($ticketId);
        if (!$ticket) {
            error_log("ERROR: Ticket not found: {$ticketId}");
            $json["message"] = "Chamado não encontrado.";
            echo json_encode($json);
            exit;
        }

        // Verifica se o usuário está autenticado
        $account = Auth::account();
        error_log("Account authenticated: " . ($account ? "Yes (ID: {$account->id})" : "No"));

        if (!$account || !$account->id) {
            error_log("ERROR: User not authenticated");
            $json["message"] = "Usuário não autenticado.";
            echo json_encode($json);
            exit;
        }

        // Cria o comentário
        $ticketComment = new TicketComment();
        $ticketComment->ticket_id = $ticketId;
        $ticketComment->user_id = $account->id;
        $ticketComment->comment = $comment;
        $ticketComment->is_internal = $isInternal;
        $ticketComment->context = $context; // 'admin' ou 'client'

        error_log("Attempting to save comment...");
        if (!$ticketComment->save()) {
            error_log("ERROR: Failed to save comment");
            $json["message"] = "Erro ao salvar comentário.";
            if ($ticketComment->fail()) {
                $failMessage = $ticketComment->fail()->getMessage();
                error_log("Fail message: " . $failMessage);
                $json["error_details"] = $failMessage;
            }
            echo json_encode($json);
            exit;
        }

        error_log("Comment saved successfully with ID: {$ticketComment->id}");

        // Registra no histórico
        TicketHistory::log(
            $ticketId,
            "comment_added",
            $account->id,
            null,
            null,
            null,
            $isInternal ? "Comentário interno adicionado" : "Comentário adicionado"
        );

        $json["success"] = true;
        $json["message"] = "Comentário adicionado!";

        error_log("Returning success response");
        echo json_encode($json);
        exit;
    }

    // Listar comentários de um ticket
    public function getTicketComments(?array $data): void
    {
        // Log para debug
        error_log("getTicketComments called with data: " . print_r($data, true));

        // Verificar autenticação
        $account = Auth::account();
        error_log("Account: " . ($account ? "Authenticated (ID: {$account->id})" : "Not authenticated"));

        if (!$account) {
            error_log("ERROR: User not authenticated");
            echo json_encode(["success" => false, "error" => "Não autenticado"]);
            return;
        }

        $ticketId = (int)($data["id"] ?? 0);
        error_log("Ticket ID: {$ticketId}");

        if (!$ticketId) {
            error_log("ERROR: Invalid ticket ID");
            echo json_encode(["success" => false, "error" => "ID inválido"]);
            return;
        }

        try {
            // Verifica se o usuário atual é um funcionário
            $currentUserIsEmployee = (new \Source\Models\App\Employee())->find("user_id = :uid", "uid={$account->id}")->fetch();
            $isClientView = !$currentUserIsEmployee; // Se não for funcionário, é cliente

            $comments = (new TicketComment())
                ->find("ticket_id = :tid", "tid={$ticketId}")
                ->order("created_at ASC")
                ->fetch(true);

            error_log("Comments found: " . ($comments ? count($comments) : 0));

            $result = [];
            if ($comments) {
                foreach ($comments as $comment) {
                    // Clientes não podem ver comentários internos
                    if ($isClientView && $comment->is_internal == 1) {
                        continue; // Pula comentários internos para clientes
                    }

                    $user = $comment->user();
                    $person = $user ? $user->person() : null;

                    // Verifica se é funcionário baseado no contexto salvo
                    // Se não tiver contexto salvo, usa a verificação pela tabela employee (retrocompatibilidade)
                    $isEmployee = false;
                    if (isset($comment->context) && $comment->context === 'admin') {
                        $isEmployee = true;
                    } elseif (!isset($comment->context) && $user) {
                        // Retrocompatibilidade: verifica na tabela employee
                        $employee = (new \Source\Models\App\Employee())->find("user_id = :uid", "uid={$user->id}")->fetch();
                        $isEmployee = $employee ? true : false;
                    }

                    $result[] = [
                        "id" => $comment->id,
                        "comment" => nl2br(htmlspecialchars($comment->comment ?? '')),
                        "is_internal" => (int)($comment->is_internal ?? 0),
                        "created_at" => $comment->created_at ? date("d/m/Y H:i", strtotime($comment->created_at)) : '',
                        "user_name" => $person ? $person->full_name : "Usuário",
                        "user_avatar" => $user && method_exists($user, 'photo') ? $user->photo() : null,
                        "is_employee" => $isEmployee
                    ];
                }
            }

            error_log("Returning " . count($result) . " comments");
            echo json_encode(["success" => true, "comments" => $result]);
        } catch (\Exception $e) {
            error_log("EXCEPTION in getTicketComments: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode(["success" => false, "error" => "Erro ao buscar comentários: " . $e->getMessage()]);
        }
    }

    // Upload de anexo
    public function uploadTicketAttachment(?array $data): void
    {
        // Limpa qualquer output anterior
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');

        $json = [];

        // Support both route formats: /chamado/anexo/upload and /chamado/{id}/anexo
        $ticketId = (int)($data["ticket_id"] ?? $data["id"] ?? $_POST["ticket_id"] ?? 0);

        if (!$ticketId) {
            $json["message"] = (new \Source\Support\Message())
                ->error("ID do chamado inválido.")
                ->toast()
                ->render();
            echo json_encode($json);
            exit;
        }

        $ticket = (new SupportTicket())->findById($ticketId);
        if (!$ticket) {
            $json["message"] = (new \Source\Support\Message())
                ->error("Chamado não encontrado.")
                ->toast()
                ->render();
            echo json_encode($json);
            exit;
        }

        // Valida o arquivo
        if (empty($_FILES["file"]) || $_FILES["file"]["size"] === 0) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("Nenhum arquivo foi enviado.")
                ->toast()
                ->render();
            echo json_encode($json);
            exit;
        }

        $file = $_FILES["file"];
        $upload = new Upload();

        // Limita tamanho (10MB)
        if ($file["size"] > 10485760) {
            $json["message"] = (new \Source\Support\Message())
                ->warning("O arquivo não pode ser maior que 10MB.")
                ->toast()
                ->render();
            echo json_encode($json);
            exit;
        }

        // Aceitar qualquer tipo de arquivo: movemos manualmente para storage/uploads/tickets
        $storageDir = "storage/uploads/tickets/" . date("Y/m/");

        if (!is_dir($storageDir) && !mkdir($storageDir, 0755, true) && !is_dir($storageDir)) {
            // Falha ao criar diretório
            $uploadPath = null;
        } else {
            $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
            try {
                $rand = bin2hex(random_bytes(6));
            } catch (\Exception $e) {
                $rand = substr(md5(uniqid((string)time(), true)), 0, 12);
            }

            $filename = "ticket-{$ticketId}-" . time() . "-{$rand}" . ($ext ? ".{$ext}" : "");
            $dest = rtrim($storageDir, "/") . "/" . $filename;

            if (is_uploaded_file($file["tmp_name"]) && move_uploaded_file($file["tmp_name"], $dest)) {
                @chmod($dest, 0644);
                // Define caminho parecido com o usado pelo Upload class (ex: storage/...)
                $uploadPath = $dest;
            } else {
                $uploadPath = null;
            }
        }

        if (!$uploadPath) {
            $json["message"] = $upload->message()
                ->toast()
                ->render();
            echo json_encode($json);
            exit;
        }

        // Salva no banco
        $attachment = new TicketAttachment();
        $attachment->ticket_id = $ticketId;
        $attachment->user_id = Auth::account()->id;
        $attachment->filename = basename($uploadPath);
        $attachment->original_name = $file["name"];

        // Armazena no DB o caminho sem o prefixo "storage/" (sem a barra inicial)
        // Ex.: storage/uploads/... => uploads/...
        $dbPath = preg_replace('#^/?storage/#', '', $uploadPath);
        $attachment->file_path = $dbPath;

        $attachment->file_size = $file["size"];
        $attachment->mime_type = $file["type"];
        $attachment->context = $data["context"] ?? $_POST["context"] ?? "client"; // 'admin' ou 'client'

        if (!$attachment->save()) {
            // Remove arquivo se falhar ao salvar no banco
            $upload->remove($uploadPath);

            $json["message"] = $attachment->message()->toast()->render();
            echo json_encode($json);
            exit;
        }

        // Registra no histórico
        TicketHistory::log(
            $ticketId,
            "attachment_added",
            Auth::account()->id,
            null,
            null,
            null,
            "Anexo: " . $file["name"]
        );

        $json["success"] = true;
        // keep message minimal to avoid UI toasts on client
        $json["message"] = "Arquivo enviado com sucesso!";
        $json["attachment"] = [
            "id" => $attachment->id,
            "filename" => $attachment->original_name,
            "size" => $attachment->formattedSize(),
            "url" => $attachment->url()
        ];

        echo json_encode($json);
        exit;
    }

    // Listar anexos de um ticket
    public function getTicketAttachments(?array $data): void
    {
        error_log("=== getTicketAttachments called ===");
        error_log("Data received: " . print_r($data, true));

        // Verificar autenticação
        $account = Auth::account();
        error_log("Account authenticated: " . ($account ? "Yes (ID: {$account->id})" : "No"));

        if (!$account) {
            echo json_encode(["success" => false, "error" => "Não autenticado"]);
            return;
        }

        $ticketId = (int)($data["id"] ?? 0);
        error_log("Ticket ID: {$ticketId}");

        if (!$ticketId) {
            echo json_encode(["success" => false, "error" => "ID inválido"]);
            return;
        }

        try {
            $attachments = (new TicketAttachment())
                ->find("ticket_id = :tid", "tid={$ticketId}")
                ->order("uploaded_at DESC")
                ->fetch(true);

            error_log("Attachments found: " . ($attachments ? count($attachments) : 0));

            $result = [];
            if ($attachments) {
                foreach ($attachments as $attachment) {
                    error_log("Processing attachment ID: {$attachment->id}, filename: {$attachment->filename}");
                    $user = $attachment->user();
                    $person = $user ? $user->person() : null;

                    // Verifica contexto (admin panel = funcionário, client portal = cliente)
                    $isEmployee = false;
                    if (isset($attachment->context) && $attachment->context === 'admin') {
                        $isEmployee = true;
                    } elseif (!isset($attachment->context) && $user) {
                        // Retrocompatibilidade: verifica tabela de funcionários para registros antigos
                        $employee = (new \Source\Models\App\Employee())->find("user_id = :uid", "uid={$user->id}")->fetch();
                        $isEmployee = $employee ? true : false;
                    }

                    $result[] = [
                        "id" => $attachment->id,
                        "filename" => $attachment->original_name ?? $attachment->filename ?? 'arquivo',
                        "formatted_size" => method_exists($attachment, 'formattedSize') ? $attachment->formattedSize() : '',
                        "url" => method_exists($attachment, 'url') ? $attachment->url() : '',
                        "is_image" => method_exists($attachment, 'isImage') ? $attachment->isImage() : false,
                        "icon" => method_exists($attachment, 'fileIcon') ? $attachment->fileIcon() : 'ki-file',
                        "uploaded_at" => $attachment->uploaded_at ? date("d/m/Y H:i", strtotime($attachment->uploaded_at)) : '',
                        "user_name" => $person ? $person->full_name : "Usuário",
                        "is_employee" => $isEmployee
                    ];
                }
            }

            error_log("Returning " . count($result) . " attachments");
            echo json_encode(["success" => true, "attachments" => $result]);
        } catch (\Exception $e) {
            error_log("Error in getTicketAttachments: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            echo json_encode(["success" => false, "error" => "Erro ao buscar anexos: " . $e->getMessage()]);
        }
    }

    // Excluir anexo
    public function deleteTicketAttachment(?array $data): void
    {
        // Limpa qualquer output anterior
        if (ob_get_level()) {
            ob_clean();
        }
        header('Content-Type: application/json');

        error_log("=== deleteTicketAttachment called ===");
        error_log("Data received: " . print_r($data, true));

        $json = [];

        $attachmentId = (int)($data["id"] ?? 0);
        error_log("Attachment ID: " . $attachmentId);

        if (!$attachmentId) {
            $json["success"] = false;
            $json["message"] = "ID inválido.";
            echo json_encode($json);
            exit;
        }

        /** @var TicketAttachment|null $attachment */
        $attachment = (new TicketAttachment())->findById($attachmentId);
        if (!$attachment) {
            error_log("Attachment not found with ID: " . $attachmentId);
            $json["success"] = false;
            $json["message"] = "Anexo não encontrado.";
            echo json_encode($json);
            exit;
        }

        error_log("Attempting to delete attachment: " . $attachment->filename);

        if (!$attachment->destroyWithFile()) {
            error_log("Failed to delete attachment");
            $json["success"] = false;
            $json["message"] = "Erro ao excluir anexo.";
            echo json_encode($json);
            exit;
        }

        error_log("Attachment deleted successfully");
        $json["success"] = true;
        $json["message"] = "Anexo excluído com sucesso!";

        echo json_encode($json);
        exit;
    }

    // Obter histórico de um ticket
    public function getTicketHistory(?array $data): void
    {
        // Verificar autenticação
        $account = Auth::account();
        if (!$account) {
            echo json_encode(["success" => false, "error" => "Não autenticado"]);
            return;
        }

        $ticketId = (int)($data["id"] ?? 0);

        if (!$ticketId) {
            echo json_encode(["success" => false, "error" => "ID inválido"]);
            return;
        }

        try {
            $history = (new TicketHistory())
                ->find("ticket_id = :tid", "tid={$ticketId}")
                ->order("created_at DESC")
                ->fetch(true);

            $result = [];
            if ($history) {
                foreach ($history as $item) {
                    $user = $item->user();
                    $person = $user ? $user->person() : null;

                    $result[] = [
                        "id" => $item->id,
                        "action" => method_exists($item, 'actionLabel') ? $item->actionLabel() : ($item->action ?? ''),
                        "icon" => method_exists($item, 'actionIcon') ? $item->actionIcon() : 'ki-time',
                        "color" => method_exists($item, 'actionColor') ? $item->actionColor() : 'primary',
                        "field_changed" => $item->field_changed ?? '',
                        "old_value" => $item->old_value ?? '',
                        "new_value" => $item->new_value ?? '',
                        "description" => $item->description ?? '',
                        "created_at" => $item->created_at ? date("d/m/Y H:i", strtotime($item->created_at)) : '',
                        "user_name" => $person ? $person->full_name : "Sistema"
                    ];
                }
            }

            echo json_encode(["success" => true, "history" => $result]);
        } catch (\Exception $e) {
            error_log("Error in getTicketHistory: " . $e->getMessage());
            echo json_encode(["success" => false, "error" => "Erro ao buscar histórico: " . $e->getMessage()]);
        }
    }

    /**
     * APP | Client Tickets List
     * 
     * @param array|null $data
     * @return void
     */
    public function myTickets(?array $data): void
    {
        // Get current user's person_id
        /** @var \Source\Models\Account|null $account */
        $account = Auth::account();
        if (!$account || !$account->person_id) {
            $this->message->warning("Usuário não encontrado.")->flash();
            redirect("/app");
            return;
        }

        // Get customer for current user using person_id
        $customer = (new \Source\Models\App\Customer())->find("person_id = :id", "id={$account->person_id}")->fetch();

        if (!$customer) {
            $this->message->warning("Você precisa ser um cliente para acessar os chamados.")->flash();
            redirect("/app");
            return;
        }

        // Get tickets for this customer (customer table uses person_id as PK, but support_ticket.customer_id references customer.id)
        // We need to check the actual FK constraint
        $tickets = (new SupportTicket())
            ->find("customer_id = :customer", "customer={$customer->person_id}")
            ->order("opened_at DESC")
            ->fetch(true);

        $this->renderPage("tickets/my-tickets", [
            "activeMenu" => "cliente",
            "activesubmenu" => "suporte",
            "tickets" => $tickets,
            "paginator" => null
        ], "Meus Chamados - " . CONF_SITE_NAME);
    }

    /**
     * APP | Create Ticket (Client)
     * 
     * @param array|null $data
     * @return void
     */
    public function createTicket(?array $data): void
    {
        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->createTicketPost($data);
            return;
        }

        // Show form
        $this->renderPage("tickets/create-ticket", [], "Abrir Chamado - " . CONF_SITE_NAME);
    }

    /**
     * APP | Create Ticket POST (Client)
     * 
     * @param array|null $data
     * @return void
     */
    private function createTicketPost(?array $data): void
    {
        $json = ["success" => false];

        // Get current user's person_id
        /** @var \Source\Models\Account|null $account */
        $account = Auth::account();
        if (!$account || !$account->person_id) {
            $json["message"] = "Usuário não encontrado.";
            echo json_encode($json);
            return;
        }

        // Get customer for current user using person_id
        $customer = (new \Source\Models\App\Customer())->find("person_id = :id", "id={$account->person_id}")->fetch();

        if (!$customer) {
            $json["message"] = "Você precisa ser um cliente para abrir chamados.";
            echo json_encode($json);
            return;
        }

        // Validate input
        $title = filter_var($_POST['title'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $description = filter_var($_POST['description'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $category = filter_var($_POST['category'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $priority = filter_var($_POST['priority'] ?? 'low', FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($title) || mb_strlen($title) > 255 || empty($description) || mb_strlen($description) < 10 || empty($category)) {
            $json["message"] = "Preencha todos os campos obrigatórios.";
            echo json_encode($json);
            return;
        }

        // Create ticket - customer_id deve ser person_id pois a FK aponta para customer(person_id)
        $ticket = new SupportTicket();
        $ticket->customer_id = $customer->person_id;
        $ticket->title = $title;
        $ticket->description = $description;
        $ticket->category = $category;
        $ticket->priority = $priority;
        $ticket->status = 'open';

        if ($ticket->save()) {
            // Registra no histórico
            TicketHistory::log(
                (int)$ticket->id,
                "created",
                $account->id,
                null,
                null,
                null,
                "Chamado criado pelo cliente"
            );

            $json["success"] = true;
            $json["message"] = "Chamado aberto com sucesso! Protocolo: #" . str_pad($ticket->id, 6, '0', STR_PAD_LEFT);
        } else {
            // Se falhar no save, verifica se é por erro de duplicata
            $json["message"] = "Erro ao abrir o chamado. Tente novamente.";
            if ($ticket->fail()) {
                $error = $ticket->fail()->getMessage();
                $json["error_details"] = $error;

                // Se for erro de constraint (duplicate), tenta recuperar o ticket criado
                if (str_contains($error, "Duplicate") || str_contains($error, "duplicate")) {
                    try {
                        $connect = \Source\Core\Connect::getInstance();
                        $sql = "SELECT id FROM support_ticket 
                                WHERE customer_id = :customer_id 
                                AND title = :title 
                                AND description = :description 
                                AND category = :category 
                                ORDER BY opened_at DESC 
                                LIMIT 1";

                        $statement = $connect->prepare($sql);
                        $statement->execute([
                            ':customer_id' => $customer->person_id,
                            ':title' => $title,
                            ':description' => $description,
                            ':category' => $category
                        ]);

                        if ($statement->rowCount() > 0) {
                            $result = $statement->fetch(\PDO::FETCH_ASSOC);
                            $json["success"] = true;
                            $json["message"] = "Chamado aberto com sucesso! Protocolo: #" . str_pad($result['id'], 6, '0', STR_PAD_LEFT);
                        }
                    } catch (\Exception $e) {
                        error_log("Recovery check failed: " . $e->getMessage());
                    }
                }
            }
        }

        echo json_encode($json);
    }

    /**
     * APP | View Single Ticket (Client)
     * 
     * @param array|null $data
     * @return void
     */
    public function viewMyTicket(?array $data): void
    {
        $ticketId = $data['id'] ?? null;

        if (!$ticketId) {
            $this->message->error("Chamado não encontrado.")->flash();
            redirect("/app/meus-chamados");
            return;
        }

        // Get current user's person_id
        /** @var \Source\Models\Account|null $account */
        $account = Auth::account();
        if (!$account || !$account->person_id) {
            $this->message->warning("Usuário não encontrado.")->flash();
            redirect("/app");
            return;
        }

        // Get customer for current user
        $customer = (new \Source\Models\App\Customer())->find("person_id = :id", "id={$account->person_id}")->fetch();

        if (!$customer) {
            $this->message->warning("Você precisa ser um cliente para acessar os chamados.")->flash();
            redirect("/app");
            return;
        }

        /** @var SupportTicket|null $ticket */
        $ticket = (new SupportTicket())->findById((int)$ticketId);

        if (!$ticket || $ticket->customer_id != $customer->person_id) {
            $this->message->error("Chamado não encontrado ou você não tem permissão para visualizá-lo.")->flash();
            redirect("/app/meus-chamados");
            return;
        }

        $this->renderPage("tickets/view-ticket", [
            "ticket" => $ticket
        ], "Chamado #{$ticket->id} - " . CONF_SITE_NAME);
    }


    /**
     * Exibe a página de Contato/Orçamento para o cliente
     * @return void
     */
    public function contact(): void
    {
        // Renderiza a view que criaremos no Passo 4
        $this->renderPage("contact/main", [
            "active"   => "contact",
            "title"    => "Solicitar Orçamento Personalizado",
            "subtitle" => "Entre em contato com nossa equipe comercial.",
        ]);
    }

    // No source/App/App.php, verifique se você tem 'use Source\Models\Auth;'

    // No topo do App.php, se tiver, remova todos os 'use' statements que eu passei (exceto Auth).
    // Vamos usar o FQN (caminho completo) para depurar a raiz do erro.

    public function contractPlan(array $data): void
    {
        try {
            $userId = Auth::account()->id;
            $newPlanId = (int)($data['plan'] ?? 0);

            // 1. Busca o Contrato Atual
            $currentContract = (new Contract())->find("customer_id = :uid AND status = 'active'", "uid={$userId}")->fetch();

            if ($currentContract) {
                $json["message"] = $this->message->error("Cliente já tem contrato ativo. Não é possível realizar a contratação.")->toast()->render();
                jsonResponse($json);
                return;
            }

            // 2. Busca Planos
            $newPlan = (new Plan())->findById($newPlanId);

            if (!$newPlan) {
                $json["message"] = $this->message->error("O plano selecionado é inválido. Tente novamente.")->toast()->render();
                jsonResponse($json);
                return;
            }

            // 4. Redireciona para a tela de pagamento / simulação.
            $json["redirect"] = url("/app/contract/payment/plan/{$newPlanId}");
            jsonResponse($json);
        } catch (\Throwable $e) {
            $this->message->error("Ocorreu um erro inesperado. Tente novamente.")->toast()->flash();
            redirect("/app");
        }
    }

    public function upgradePlan(array $data): void
    {
        try {
            $userId = Auth::account()->id;
            $newPlanId = (int)($data['plan'] ?? 0);

            // 1. Busca o Contrato Atual
            $currentContract = (new Contract())->find("customer_id = :uid AND status = 'active'", "uid={$userId}")->fetch();

            if (!$currentContract) {
                $json["message"] = $this->message->error("Cliente sem contrato ativo. Não é possível realizar o upgrade.")->toast()->render();
                jsonResponse($json);
                return;
            }

            // 2. Busca Planos
            $currentPlan = $currentContract->plan();
            $newPlan = (new Plan())->findById($newPlanId);

            if (!$currentPlan || !$newPlan) {
                $json["message"] = $this->message->error("O plano selecionado é inválido. Tente novamente.")->toast()->render();
                jsonResponse($json);
                return;
            }

            // 3. VALIDAÇÃO: Garante que é um UPGRADE (preço superior)
            if ($newPlan->price <= $currentPlan->price) {
                $json["message"] = $this->message->warning("Você deve selecionar um plano superior para realizar um upgrade.")->toast()->render();
                jsonResponse($json);
                return;
            }

            // 4. Redireciona para a tela de pagamento / simulação.
            $json["redirect"] = url("/app/payment/plan/{$newPlanId}");
            jsonResponse($json);
        } catch (\Throwable $e) {
            $this->message->error("Ocorreu um erro inesperado. Tente novamente.")->toast()->flash();
            redirect("/app");
        }
    }

    public function paymentSimulate(array $data): void
    {
        $planId = (int)($data['planId'] ?? 0);
        $newPlan = (new \Source\Models\App\Plan())->findById($planId);

        if (!$newPlan) {
            $this->message->error("Plano de upgrade inválido.")->toast()->flash();
            redirect("/app");
            return;
        }

        $this->renderPage("plans/payment", [ // Nova View que vamos criar
            "active"   => "home",
            "title"    => "Simulação de Pagamento",
            "subtitle" => "Conclua o pagamento para ativar o upgrade.",
            "newPlan"  => $newPlan
        ]);
    }

    public function contractPaymentSimulate(array $data): void
    {
        $planId = (int)($data['planId'] ?? 0);
        $newPlan = (new \Source\Models\App\Plan())->findById($planId);

        if (!$newPlan) {
            $this->message->error("Plano inválido.")->toast()->flash();
            redirect("/app");
            return;
        }

        $this->renderPage("plans/contract-payment", [ // Nova View que vamos criar
            "active"   => "home",
            "title"    => "Simulação de Pagamento",
            "subtitle" => "Conclua o pagamento para ativar o plano.",
            "newPlan"  => $newPlan
        ]);
    }


// No source/App/App.php

    /**
     * [ETAPA 3] - Processa a simulação de pagamento e atualiza o contrato no DB.
     */
    public function upgradeProcess(array $data): void
    {
        $userId = Auth::account()->id;
        $newPlanId = (int)($data['planId'] ?? 0);

        // 1. Busca o contrato atual e o novo plano (USANDO NOME CURTO: Contract e Plan)
        $currentContract = (new Contract())->find("customer_id = :uid AND status = 'active'", "uid={$userId}")->fetch();
        $newPlan = (new Plan())->findById($newPlanId);

        if (!$currentContract || !$newPlan) {
            $json["message"] = $this->message->error("Falha ao localizar os dados do plano ou contrato. Tente o upgrade novamente.")->toast()->render();
            jsonResponse($json);
            return;
        }

        // 2. Atualização do Contrato (Simulação de Sucesso)
        $currentContract->plan_id = $newPlanId;

        if (!$currentContract->save()) {
            // Mensagem de erro mais robusta
            $errorMsg = $currentContract->message()->getText() ?? "Erro desconhecido ao salvar o novo plano. Contate o suporte.";
            $json["message"] = $this->message->error("Erro ao salvar o novo plano: " . $errorMsg)->toast()->render();
            jsonResponse($json);
            return;
        }

        // 3. Sucesso! Redireciona para a tela final de sucesso.
        $this->message->success("Parabéns! O seu plano foi atualizado para: {$newPlan->name}!")->toast()->flash();
        $json["redirect"] = url("/app/upgrade/success");
        jsonResponse($json);
        return;
    }

    public function contractProcess(array $data): void
    {
        $json = [];

        try {
            $account = Auth::account();
            $userId = $account->id;
            $planId = (int)($data['planId'] ?? $data['plan'] ?? 0);

            if (!$planId) {
                $json["message"] = $this->message->error("Plano inválido.")->toast()->render();
                jsonResponse($json);
                return;
            }

            $plan = (new Plan())->findById($planId);
            if (!$plan) {
                $json["message"] = $this->message->error("Plano não encontrado.")->toast()->render();
                jsonResponse($json);
                return;
            }

            // Garante que exista um registro de customer para esta pessoa
            $customer = (new Customer())->find("person_id = :pid", "pid={$userId}")->fetch();
            if (!$customer) {
                $customer = new Customer();
                $customer->person_id = $userId;
                $customer->status = "active";
                if (!$customer->save()) {
                    $json["message"] = $customer->message()->toast()->render();
                    jsonResponse($json);
                    return;
                }
            }

            // Verifica se já existe contrato ativo
            $existing = (new Contract())->find("customer_id = :cid AND status = 'active'", "cid={$userId}")->fetch();
            if ($existing) {
                $json["message"] = $this->message->error("Cliente já possui um contrato ativo.")->toast()->render();
                jsonResponse($json);
                return;
            }

            // Cria novo contrato (contratação)
            $contract = new Contract();
            $contract->customer_id = $userId; // Nota: nesta base customer_id guarda person_id
            $contract->plan_id = $planId;
            $contract->start_date = date("Y-m-d");
            $contract->status = "active";

            if (!$contract->save()) {
                $json["message"] = $contract->message()->toast()->render();
                jsonResponse($json);
                return;
            }

            $this->message->success("Plano contratado com sucesso!")->toast()->flash();
            $json["redirect"] = url("/app/contract/success");
            jsonResponse($json);
            return;
        } catch (\Throwable $e) {
            error_log("Erro em contractProcess: " . $e->getMessage());
            $this->message->error("Ocorreu um erro ao contratar o plano. Tente novamente.")->toast()->flash();
            redirect("/app");
        }
    }

    /**
     * Exibe a página de sucesso após o upgrade simulado
     */
    public function upgradeSuccess(): void
    {
        // A view 'plans/success' precisa ser criada na pasta themes/app/plans/
        $this->renderPage("plans/success", [
            "active"   => "home",
            "title"    => "Upgrade Concluído",
            "subtitle" => "Seu novo plano já está ativo!"
        ]);
    }

    /**
     * Exibe a página de sucesso após o upgrade simulado
     */
    public function contractSuccess(): void
    {
        // A view 'plans/success' precisa ser criada na pasta themes/app/plans/
        $this->renderPage("plans/contract-success", [
            "active"   => "home",
            "title"    => "Contratação Concluída",
            "subtitle" => "Seu novo plano já está ativo!"
        ]);
    }


    /** APP | Logout */
    public function logout(): void
    {
        Auth::logout();
        redirect("/entrar");
    }
}
