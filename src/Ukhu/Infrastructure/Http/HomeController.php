<?php

namespace App\Ukhu\Infrastructure\Http;

use App\Ukhu\Application\Ports\TemplateInterface;
use PDO;

class HomeController extends Controller
{
    public function __construct(TemplateInterface $template)
    {
        parent::__construct($template);
    }

    public function index()
    {
        $data = [];

        return $this->view('home.html', $data);
    }
}
