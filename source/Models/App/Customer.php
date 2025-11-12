<?php

namespace Source\Models\App;

use Source\Core\Model;
use Source\Models\Person;

class Customer extends Model
{
    public function __construct()
    {
        parent::__construct("customer", ["id"], ["person_id", "status"]);
    }

    /**
     * Retorna a pessoa associada
     */
    public function person(): ?Person
    {
        return !empty($this->person_id)
            ? (new Person())->findById((int)$this->person_id)
            : null;
    }

    /**
     * Retorna a conta (Account) associada
     */
    public function account(): ?\Source\Models\Account
    {
        return (new \Source\Models\Account())
            ->find("person_id = :pid", "pid={$this->person_id}")
            ->fetch() ?: null;
    }

    /**
     * Retorna o contrato ativo (objeto Contract)
     */
    public function contract(): ?\Source\Models\App\Contract
    {
        return (new \Source\Models\App\Contract())
            ->find("customer_id = :cid AND status = 'active'", "cid={$this->person_id}")
            ->order("id DESC")
            ->fetch() ?: null;
    }

    /**
     * Retorna o plano ativo (Plan) via contrato
     */
    public function plan(): ?\Source\Models\App\Plan
    {
        $contract = $this->contract();
        if ($contract && !empty($contract->plan_id)) {
            return (new \Source\Models\App\Plan())->findById((int)$contract->plan_id);
        }
        return null;
    }

    /**
     * Retorna o equipamento atualmente alocado (Equipment)
     */
    public function equipment(): ?\Source\Models\App\Equipment
    {
        // Busca o vínculo ativo do equipamento diretamente pelo person_id
        $customerEq = (new \Source\Models\App\CustomerEquipment())
            ->find("customer_id = :cid AND (end_date IS NULL OR end_date = '0000-00-00')", "cid={$this->person_id}")
            ->order("start_date DESC")
            ->fetch();

        if ($customerEq && !empty($customerEq->equipment_id)) {
            return (new \Source\Models\App\Equipment())->findById((int)$customerEq->equipment_id);
        }

        return null;
    }

    public function isActive(): bool
    {
        $contract = $this->contract();
        return $contract && $contract->status === 'active';
    }
}
