<?php
namespace App\Controller;

class DashboardController
{
    public function index()
    {
        // Método para carregar a view do dashboard.
        require __DIR__ . '/../View/dashboard/index.php';
    }
}