<?php

namespace App\Ukhu\Infrastructure\Http;

use App\Ukhu\Application\Ports\TemplateInterface;
use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;

abstract class Controller
{
    private $template;

    public function __construct(TemplateInterface $template)
    {
        $this->template = $template;
    }

    /**
     * Renders $template_filename with given $data
     * and returns it as Laminas\Diactoros\Response
     *
     * @param string $template_filename
     * @param array $data
     * @return ResponseInterface
     */
    protected function view(string $template_filename, array $data = []): ResponseInterface
    {
        $result = $this->template->render($template_filename, $data);

        $response = new Response();
        $response->getBody()->write($result);
        return $response;
    }
}
