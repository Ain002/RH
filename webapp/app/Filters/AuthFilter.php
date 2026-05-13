<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Roles autorisés par filtre :
     *   auth        → juste être connecté (n'importe quel rôle)
     *   role:admin  → admin uniquement
     *   role:rh     → rh uniquement
     *   role:employe→ employe uniquement
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Pas connecté → login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/')->with('error', 'Veuillez vous connecter.');
        }

        // 2. Vérification de rôle si précisé
        if (!empty($arguments)) {
            $userRole = $session->get('user')['role'] ?? 'employe';

            foreach ($arguments as $arg) {
                if (str_starts_with($arg, 'role:')) {
                    $requiredRole = substr($arg, 5);
                    if ($userRole !== $requiredRole) {
                        return redirect()->to('/unauthorized')
                            ->with('error', 'Accès refusé : permission insuffisante.');
                    }
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Rien à faire après
    }
}