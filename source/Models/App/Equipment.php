<?php

namespace Source\Models\App;

use Source\Core\Model;

class Equipment extends Model
{
    public function __construct()
    {
        parent::__construct("equipment", ["id"], ["type"]);
    }

    /**
     * Retorna uma breve descrição do equipamento
     */
    public function summary(): string
    {
        $parts = array_filter([$this->type, $this->manufacturer, $this->model, $this->serial_number]);
        return implode(" - ", $parts);
    }
}
