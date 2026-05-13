<?php

namespace App\Controllers;

use App\Models\EmployeModel;
use App\Models\CongeModel;
use App\Models\DepartementModel;
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
        return view('employe/index');
    }

    public function employeDashboard(): string
    {
        return view('employe/dashboard');
    }

    public function create(): string
    {
        return view('employe/create');
    }

    public function admin()
    {
        $employeModel = new EmployeModel();
        $departementModel = new DepartementModel();
        $soldeModel = new SoldeModel();

        // Récupérer tous les employés avec leurs infos de soldes
        $employes = $employeModel->findAll();
        
        // Enrichir avec les informations de département et soldes
        $employes_data = [];
        foreach ($employes as $employe) {
            $dept = $departementModel->find($employe['departement_id']);
            
            // Récupérer le solde annuel
            $solde = $soldeModel->where('employe_id', $employe['id'])
                               ->where('type_conge_id', 1) // Supposer que ID 1 = congé annuel
                               ->first();
            
            $employe['departement_nom'] = $dept['nom'] ?? 'N/A';
            $employe['solde_annuel_attribue'] = $solde['jours_attribues'] ?? 30;
            $employe['solde_annuel_pris'] = $solde['jours_pris'] ?? 0;
            
            $employes_data[] = $employe;
        }
        
        // Récupérer les départements pour le formulaire
        $departements = $departementModel->findAll();

        return view('admin/employes', [
            'employes' => $employes_data,
            'departements' => $departements,
        ]);
    }

    public function adminDashboard()
    {
        $employeModel = new EmployeModel();
        $congeModel = new CongeModel();
        $departementModel = new DepartementModel();

        // Métriques
        $totalEmployes = $employeModel->where('actif', 1)->countAllResults();
        
        // Récupérer les congés avec noms d'employés pour les demandes récentes
        $conges = $congeModel->select('conges.*, 
                                       employes.nom as employe_nom, 
                                       employes.prenom as employe_prenom,
                                       types_conge.libelle as type_conge_libelle')
                             ->join('employes', 'employes.id = conges.employe_id')
                             ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                             ->limit(3)
                             ->orderBy('conges.id', 'DESC')
                             ->findAll();

        $attentes = $congeModel->where('statut', 'en_attente')->countAllResults();
        $approuvees = $congeModel->where('statut', 'approuve')->countAllResults();
        $departements = $departementModel->countAllResults();

        // Récupérer les employés absents (congés approuvés pour aujourd'hui)
        $today = date('Y-m-d');
        $absents = $congeModel->select('employes.nom, employes.prenom, 
                                        types_conge.libelle as type_conge,
                                        conges.date_fin')
                              ->join('employes', 'employes.id = conges.employe_id')
                              ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                              ->where('conges.statut', 'approuve')
                              ->where('conges.date_debut <=', $today)
                              ->where('conges.date_fin >=', $today)
                              ->findAll();

        return view('admin/dashboard', [
            'totalEmployes' => $totalEmployes,
            'attentes' => $attentes,
            'approuvees' => $approuvees,
            'absents' => $absents,
            'departements' => $departements,
            'conges_recentes' => $conges,
        ]);
    }

    public function addEmploye()
    {
        if (!$this->request->isAjax()) {
            return redirect()->to('/admin');
        }

        $employeModel = new EmployeModel();
        $soldeModel = new SoldeModel();
        $typeCongeModel = new TypeCongeModel();

        $règles = [
            'prenom' => 'required|string|min_length[2]',
            'nom' => 'required|string|min_length[2]',
            'email' => 'required|valid_email|is_unique[employes.email]',
            'password' => 'required|string|min_length[6]',
            'departement_id' => 'required|integer',
            'role' => 'required|in_list[employe,rh,admin]',
            'date_embauche' => 'required|valid_date[Y-m-d]',
        ];

        if (!$this->validate($règles)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        // Créer l'employé
        $employe_id = $employeModel->insert([
            'nom' => $this->request->getPost('nom'),
            'prenom' => $this->request->getPost('prenom'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'departement_id' => $this->request->getPost('departement_id'),
            'date_embauche' => $this->request->getPost('date_embauche'),
            'actif' => 1,
        ], true);

        // Initialiser les soldes pour tous les types de congé
        $typesConge = $typeCongeModel->findAll();
        foreach ($typesConge as $type) {
            $soldeModel->insert([
                'employe_id' => $employe_id,
                'type_conge_id' => $type['id'],
                'annee' => date('Y'),
                'jours_attribues' => $type['jours_annuels'],
                'jours_pris' => 0,
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Employé créé avec succès',
            'employe_id' => $employe_id,
        ]);
    }

    public function unauthorized(): string
    {
        return view('errors/unauthorized');
    }
}
