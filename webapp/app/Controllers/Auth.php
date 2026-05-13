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
        // Si déjà connecté, rediriger selon le rôle
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('user')['role'] ?? 'employe');
        }
        return view('auth/login');
    }

    public function authenticate()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/');
        }

        $session = session();
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validation
        if (!$this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Authentification
        $user = $this->authFunctions->authenticate($email, $password);

        if ($user) {
            $displayName = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
            if ($displayName === '') {
                $displayName = $user['nom'] ?? '';
            }

            $session->set([
                'user_id'   => $user['id'],
                'nom'       => $displayName,
                'email'     => $user['email'],
                'user'      => $user,
                'isLoggedIn' => true,
            ]);

            $role = $user['role'] ?? 'employe';
            return $this->redirectByRole($role, 'Bienvenue ' . $displayName . ' !');
        }

        return redirect()->back()->withInput()->with('error', 'Email ou mot de passe incorrect.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Vous avez été déconnecté.');
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Redirige vers le bon espace selon le rôle.
     */
    private function redirectByRole(string $role, string $successMsg = '')
    {
        $map = [
            'admin'   => '/admin',
            'rh'      => '/rh',
            'employe' => '/employe',
        ];

        $url = $map[$role] ?? '/employe';

        $redirect = redirect()->to($url);
        if ($successMsg) {
            $redirect = $redirect->with('success', $successMsg);
        }
        return $redirect;
    }
}