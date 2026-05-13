<?php

namespace App\Controllers;

use App\Models\CongeModel;
use App\Models\SoldeModel;

class CongeController extends BaseController
{
    public function findAll()
    {
        $option = $this->request->getGet('option') ?? 0;

        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        // Récupérer les congés avec les relations
        $conges = $congeModel->select('conges.*, 
                                       employes.nom as employe_nom, 
                                       employes.prenom as employe_prenom,
                                       employes.email as employe_email,
                                       types_conge.libelle as type_conge_libelle,
                                       rh.nom as rh_nom,
                                       rh.prenom as rh_prenom')
                             ->join('employes', 'employes.id = conges.employe_id')
                             ->join('types_conge', 'types_conge.id = conges.type_conge_id')
                             ->join('employes as rh', 'rh.id = conges.traite_par', 'left')
                             ->findAll();

        // Enrichir avec les informations de soldes
        $all = [];
        foreach ($conges as $conge) {
            $solde = $soldeModel->where('employe_id', $conge['employe_id'])
                               ->where('type_conge_id', $conge['type_conge_id'])
                               ->first();

            if ($solde) {
                $conge['jours_attribues'] = $solde['jours_attribues'];
                $conge['jours_pris'] = $solde['jours_pris'];
                $conge['annee'] = $solde['annee'];
            } else {
                $conge['jours_attribues'] = 0;
                $conge['jours_pris'] = 0;
                $conge['annee'] = null;
            }
            $all[] = $conge;
        }

        $soldes = $soldeModel->findAll();
        $attentes = [];
        $approuvees = [];
        $refusees = [];

        foreach ($all as $data) {
            if ($data['statut'] == 'en_attente') {
                $attentes[] = $data;
            } else if ($data['statut'] == 'approuve') {
                $approuvees[] = $data;
            } else {
                $refusees[] = $data;
            }
        }

        return view('rh/index', [
            'all' => $all,
            'attentes' => $attentes,
            'approuvees' => $approuvees,
            'refusees' => $refusees,
            'option' => $option,
            'soldes' => $soldes,
        ]);
    }

    public function approuver()
    {
        $congeId = $this->request->getPost('conge_id');

        $congeModel = new CongeModel();
        $soldeModel = new SoldeModel();

        $conge = $congeModel->find($congeId);

        if (!$conge) {
            return redirect()->back()->with('error', 'Congé non trouvé');
        }

        // Vérifier les soldes
        $solde = $soldeModel->where('employe_id', $conge['employe_id'])
                            ->where('type_conge_id', $conge['type_conge_id'])
                            ->first();

        if (!$solde) {
            return redirect()->back()->with('error', 'Solde non trouvé pour cet employé');
        }

        $joursDisponibles = ($solde['jours_attribues'] - $solde['jours_pris']);

        if ($conge['nb_jours'] > $joursDisponibles) {
            return redirect()->back()->with('error', 'Soldes insuffisants');
        }

        // Mettre à jour le congé
        $traiteParId = session()->get('user_id') ?? null;

        $congeModel->update($congeId, [
            'statut' => 'approuve',
            'traite_par' => $traiteParId,
            'commentaire_rh' => 'approuvé'
        ]);

        // Mettre à jour les soldes
        $soldeModel->where('employe_id', $conge['employe_id'])
                   ->where('type_conge_id', $conge['type_conge_id'])
                   ->set(['jours_pris' => $solde['jours_pris'] + $conge['nb_jours']])
                   ->update();

        return redirect()->to('/rh')->with('success', 'Demande de congé approuvée. Les soldes ont été mis à jour.');
    }

    public function refuser()
    {
        $congeId = $this->request->getPost('conge_id');
        $commentaire = $this->request->getPost('commentaire') ?? '';

        $congeModel = new CongeModel();

        $conge = $congeModel->find($congeId);

        if (!$conge) {
            return redirect()->back()->with('error', 'Congé non trouvé');
        }

        $traiteParId = session()->get('user_id') ?? null;

        $congeModel->update($congeId, [
            'statut' => 'refusee',
            'traite_par' => $traiteParId,
            'commentaire_rh' => $commentaire
        ]);

        return redirect()->to('/rh')->with('success', 'Demande de congé refusée.');
    }

    public function soumettreDemande()
    {
        $typeCongeId = $this->request->getPost('type_conge_id');
        $dateDebut = $this->request->getPost('date_debut');
        $dateFin = $this->request->getPost('date_fin');
        $motif = $this->request->getPost('commentaire');

        if (!$typeCongeId || !$dateDebut || !$dateFin) {
            return redirect()->back()->with('error', 'Veuillez remplir tous les champs obligatoires.');
        }

        $session = session();
        $employeId = $session->get('user_id');
        $nbJours = (strtotime($dateFin) - strtotime($dateDebut)) / (60 * 60 * 24) + 1;

        $demandeData = [
            'employe_id' => $employeId,
            'type_conge_id' => $typeCongeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nb_jours' => $nbJours,
            'motif' => $motif,
            'statut' => 'en_attente',
        ];

        $congeModel = new CongeModel();
        if ($congeModel->save($demandeData)) {
            return redirect()->to('/employe/dashboard')->with('success', 'Demande soumise avec succès.');
        }

        return redirect()->back()->with('error', 'Une erreur est survenue lors de la soumission de la demande.');
    }
}
