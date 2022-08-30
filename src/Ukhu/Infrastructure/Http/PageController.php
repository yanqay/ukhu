<?php

namespace App\Ukhu\Infrastructure\Http;

use App\Ukhu\Application\Ports\TemplateInterface;

class PageController extends Controller
{
    public function __construct(TemplateInterface $template)
    {
        parent::__construct($template);
    }

    public function about()
    {
        $data = [
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam facilis quam, aspernatur fugit officiis quia harum ut at placeat, vitae nesciunt doloribus repellat eum dolorem molestiae ad dicta, cupiditate ullam?'
        ];

        return $this->view('about.html', $data);
    }
}
