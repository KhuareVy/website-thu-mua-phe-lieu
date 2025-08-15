<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $data = ['title' => 'Trang chủ'];
        $this->view('home/index', $data);
    }
}
