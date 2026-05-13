<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\SoldeModel;
use App\Models\TypeCongeModel;

class Home extends BaseController
{
    public function index(): string
    {
        return view('auth/login');
    }

    public function rh(): string
    {
        return view('rh/index');
    }

    public function employe(): string
    {
        $session = session();
        $userId = $session->get('user_id');
        $user = $session->get('user');
        // Récupérer les congés de l'employé
        $congeModel = new CongeModel();
        $demandes = $congeModel->where('employe_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();
        return view('employe/dashboard', [
            'user' => $user,
            'demandes' => $demandes,
        ]);
    }

    public function employeDashboard(): string
    {
        $session = session();
        $userId = $session->get('user_id');
        $user = $session->get('user');

        // Récupérer les congés de l'employé
        $congeModel = new CongeModel();
        $demandes = $congeModel->where('employe_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // Enrichir les demandes avec les types de congé
        $typeCongeModel = new TypeCongeModel();
        foreach ($demandes as &$demande) {
            $type = $typeCongeModel->find($demande['type_conge_id']);
            $demande['type_libelle'] = $type['libelle'] ?? '';
        }

        // Récupérer les soldes
        $soldeModel = new SoldeModel();
        $soldes = $soldeModel->where('employe_id', $userId)
            ->where('annee', date('Y'))
            ->findAll();

        // Enrichir les soldes avec les types de congé
        foreach ($soldes as &$solde) {
            $type = $typeCongeModel->find($solde['type_conge_id']);
            $solde['type_libelle'] = $type['libelle'] ?? '';
        }

        // Calculer les stats
        $stats = [
            'en_attente' => count(array_filter($demandes, fn($d) => $d['statut'] === 'en_attente')),
            'approuvees' => count(array_filter($demandes, fn($d) => $d['statut'] === 'approuvee')),
            'refusees' => count(array_filter($demandes, fn($d) => $d['statut'] === 'refusee')),
        ];

        return view('employe/dashboard', [
            'user' => $user,
            'demandes' => $demandes,
            'soldes' => $soldes,
            'stats' => $stats,
        ]);
    }

    public function employeList(): string
    {
        $session = session();
        $userId = $session->get('user_id');
        $user = $session->get('user');

        // Récupérer TOUTES les congés de l'employé (pas limité)
        $congeModel = new CongeModel();
        $demandes = $congeModel->where('employe_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Enrichir les demandes avec les types de congé
        $typeCongeModel = new TypeCongeModel();
        foreach ($demandes as &$demande) {
            $type = $typeCongeModel->find($demande['type_conge_id']);
            $demande['type_libelle'] = $type['libelle'] ?? '';
        }

        return view('employe/index', [
            'user' => $user,
            'demandes' => $demandes,
        ]);
    }

    public function create(): string
    {
        return view('employe/create');
    }

    public function admin(): string
    {
        return view('admin/employes');
    }

    public function adminDashboard(): string
    {
        return view('admin/dashboard');
    }

    public function unauthorized(): string
    {
        return view('errors/unauthorized');
    }
}
