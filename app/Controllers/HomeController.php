<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;

class HomeController extends Controller
{
    public function home(Request $request): Response
    {
        $this->view->setLayout('layouts/default/main');
        $data = [
            'title' => 'Trang chủ',
        ];
        return $this->render('home', $data);
    }
}