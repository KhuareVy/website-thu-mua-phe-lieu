<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $data = ['title' => 'Trang chủ'];
        return $this->render('home', $data);
    }
}
