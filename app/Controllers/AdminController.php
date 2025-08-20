<?php
namespace App\Controllers;

use App\Core\Controller;

class AdminController extends Controller
{
    public function showAdmin()
    {
        return $this->view('admin/dashboard');
    }
}
