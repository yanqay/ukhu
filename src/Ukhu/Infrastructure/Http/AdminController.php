<?php

namespace App\Ukhu\Infrastructure\Http;

class AdminController extends Controller
{
    public function index()
    {
        $data = [
        ];

        return $this->view('admin.html', $data);
    }
}
