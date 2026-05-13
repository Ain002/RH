<?php

namespace App\Functions;

use App\Models\TypeCongeModel;

class TypeCongerFunction
{
    private $typeCongeModel;

    public function __construct()
    {
        $this->typeCongeModel = new TypeCongeModel();
    }

    public function getAllTypesConge()
    {
        return $this->typeCongeModel->findAll();
    }

    public function getTypeCongeById($id)
    {
        return $this->typeCongeModel->find($id);
    }

    public function getCongesDeductiblesAnnuel()
    {
        return $this->typeCongeModel->where('deductible', true)->findAll();
    }

    public function getCongesDeductiblesMaladie()
    {
        return $this->typeCongeModel->where('deductible', false)->findAll();
    }

    public function getCongesDeductiblesMaternite()
    {
        return $this->typeCongeModel->where('deductible', false)->findAll();
    }
}