<?php

namespace App\Controllers;


class CongeController extends BaseController
{
    public function soumettreDemande()
    {
        $typeCongeId = $this->request->getPost('type_conge_id');
        $dateDebut = $this->request->getPost('date_debut');
        $dateFin = $this->request->getPost('date_fin');
        $motif = $this->request->getPost('commentaire');

        // Validate input
        if (!$typeCongeId || !$dateDebut || !$dateFin) {
            return redirect()->back()->with('error', 'Veuillez remplir tous les champs obligatoires.');
        }

        // Get current user ID from session
        $session = session();
        $employeId = $session->get('user_id');

        // Calculate number of days
        $nbJours = (strtotime($dateFin) - strtotime($dateDebut)) / (60 * 60 * 24) + 1;

        // Create new demande
        $demandeData = [
            'employe_id' => $employeId,
            'type_conge_id' => $typeCongeId,
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'nb_jours' => $nbJours,
            'motif' => $motif,
            'statut' => 'en_attente',
        ];

        // Save demande to database
        $congeModel = new \App\Models\CongeModel();
        if ($congeModel->save($demandeData)) {
            return redirect()->to('/employe/dashboard')->with('success', 'Demande soumise avec succès.');
        } else {
            return redirect()->back()->with('error', 'Une erreur est survenue lors de la soumission de la demande.');
        }
    }
}