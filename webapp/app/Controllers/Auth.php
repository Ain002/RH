<?php

namespace App\Controllers;

use App\Functions\AuthFunctions;

class Auth extends BaseController
{
    private $authFunctions;

    public function __construct()
    {
        $this->authFunctions = new AuthFunctions();
    }
    public function login()
    {
        return view('auth/login');
    }


    public function authenticate()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/login');
        }

        $session = session();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validation
        if (!$this->validate([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Authenticate using AuthFunctions
        $user = $this->authFunctions->authenticate($email, $password);

        if ($user) {
            $displayName = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
            if ($displayName === '') {
                $displayName = $user['nom'] ?? '';
            }

            // Connexion réussie
            $session->set([
                'user_id' => $user['id'],
                'nom' => $displayName,
                'email' => $user['email'],
                'user' => $user,
                'isLoggedIn' => true,
            ]);

            // Si profil incomplet, rediriger vers la complétion
            if (empty($user['taille']) || empty($user['poids'])) {
                return redirect()->to('/profile/complete')->with('success', 'Bienvenue ' . $displayName . '! Complétez votre profil.');
            }

            return redirect()->to('/recommendation')->with('success', 'Bienvenue ' . $displayName . '!');
        } else {
            // Identifiants incorrects
            return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Vous avez été déconnecté.');
    }

    public function registerSubmit()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/register');
        }

        // Validation côté serveur
        if (!$this->validate([
            'nom' => 'required|min_length[2]|max_length[255]',
            'prenom' => 'required|min_length[2]|max_length[255]',
            'date_naissance' => 'required',
            'genre' => 'required|in_list[H,F]',
            'taille' => 'required|numeric|greater_than[0]',
            'poids' => 'required|numeric|greater_than[0]',
            'objectif' => 'required|in_list[lose_weight,ideal_bmi,gain_weight]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'password_confirm' => 'required|matches[password]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $nom = trim((string) $this->request->getPost('nom'));
        $prenom = trim((string) $this->request->getPost('prenom'));
        $dateNaissance = $this->request->getPost('date_naissance');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $genre = $this->request->getPost('genre');
        $taille = (float) $this->request->getPost('taille');
        $poids = (float) $this->request->getPost('poids');
        $objectif = $this->request->getPost('objectif');

        // Register using AuthFunctions
        $insertId = $this->authFunctions->register($nom, $prenom, $dateNaissance, $email, $password, $genre, $taille, $poids);

        if ($insertId) {
            $this->authFunctions->saveUserObjective($insertId, $objectif);

            $user = $this->authFunctions->getUserById($insertId);
            $displayName = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
            if ($displayName === '') {
                $displayName = $user['nom'] ?? '';
            }
            session()->set([
                'user_id' => $user['id'],
                'nom' => $displayName,
                'email' => $user['email'],
                'user' => $user,
                'isLoggedIn' => true,
            ]);

            return redirect()->to('/')->with('success', 'Inscription réussie. Bienvenue ' . $displayName . '!');
        }

        return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'inscription.');
    }

    public function completeProfile()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $user = $this->authFunctions->getUserById($session->get('user_id'));

        return view('auth/complete_profile', ['user' => $user]);
    }

    

    
}
