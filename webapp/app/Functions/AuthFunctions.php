<?php

namespace App\Functions;

use App\Models\EmployeModel;
use Config\Database;

class AuthFunctions
{
    private $employeModel;

    public function __construct()
    {
        $this->employeModel = new EmployeModel();
    }

    /**
     * Authenticate user with email and password
     * Returns user data or null
     */
    public function authenticate($email, $password)
    {
        $user = $this->employeModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }

    

    
    /**
     * Get user by ID
     */
    public function getUserById($userId)
    {
        return $this->employeModel->find($userId);
    }

    

   

    /**
     * Check if email exists
     */
    public function emailExists($email)
    {
        return $this->employeModel->where('email', $email)->first() !== null;
    }
}
