<?php

namespace App\Functions;

use App\Models\CongeModel;
use App\Functions\TypeCongerFunction;

class CongeFonction{
    private $congeModel;
    private $typeCongerFunction;

    public function __construct()
    {
        $this->congeModel = new CongeModel();
        $this->typeCongerFunction = new TypeCongerFunction();
    }

    public function getAllCongesByEmployeId($employeId)
    {
        return $this->congeModel->where('employe_id', $employeId)->findAll();
    }

    public function getCongesEnAttenteByEmployeId($employeId)
    {
        return $this->congeModel->where('employe_id', $employeId)->where('status', 'en_attente')->findAll();
    }

    public function getCongesAccepteByEmployeId($employeId)
    {
        return $this->congeModel->where('employe_id', $employeId)->where('status', 'accepte')->findAll();
    }

    public function getCongesRefuseByEmployeId($employeId)
    {
        return $this->congeModel->where('employe_id', $employeId)->where('status', 'refuse')->findAll();
    }

    public function getResteCongesByEmployeId($employeId)
    {
        $congesPris = $this->congeModel->where('employe_id', $employeId)->where('status', 'accepte')->findAll();
        $joursPris = 0;
        foreach ($congesPris as $conge) {
            $joursPris += $conge['nb_jours'];
        }
        return $joursPris;
    }

    public function getResteCongesAnnuelsByEmployeId($employeId)
    {
        $joursAttribues = $this->typeCongerFunction->getCongesDeductiblesAnnuel(); // Supposons que chaque employé a droit à 30 jours de congé annuel
        $joursPris = $this->getResteCongesByEmployeId($employeId);
        return $joursAttribues - $joursPris;
    }

    public function getResteCongesMaladieByEmployeId($employeId)
    {
        $joursAttribues = $this->typeCongerFunction->getCongesDeductiblesMaladie(); // Supposons que chaque employé a droit à 15 jours de congé maladie
        $joursPris = $this->getResteCongesByEmployeId($employeId);
        return $joursAttribues - $joursPris;
    }

    public function getResteCongesMaterniteByEmployeId($employeId)
    {
        $joursAttribues = $this->typeCongerFunction->getCongesDeductiblesMaternite(); // Supposons que chaque employé a droit à 90 jours de congé maternité
        $joursPris = $this->getResteCongesByEmployeId($employeId);
        return $joursAttribues - $joursPris;
    }
}