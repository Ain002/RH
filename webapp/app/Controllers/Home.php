<?php

namespace App\Controllers;

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
