<?php

namespace Source\Models\App;

use Source\Core\Model;

class CustomerEquipment extends Model
{
    public function __construct()
    {
        parent::__construct("customer_equipment", ["id"], ["start_date"]);
    }
}
