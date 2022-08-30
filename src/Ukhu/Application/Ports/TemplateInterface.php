<?php

declare(strict_types=1);

namespace App\Ukhu\Application\Ports;

interface TemplateInterface
{
    /**
     * @param string $template
     * @param array<string, mixed> $data
     * @return string
     */
    public function render(string $template, array $data = []): string;

    /**
     * @param mixed $value The global value
     */
    public function addGlobal(string $name, $value);
}
