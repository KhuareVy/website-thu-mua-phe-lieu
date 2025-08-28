<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;

class AdminController extends Controller
{
    public function dashboard(): Response
    {
        $this->view->setLayout('layouts/admin/main');
        return $this->render('admin/dashboard');
    }
}
