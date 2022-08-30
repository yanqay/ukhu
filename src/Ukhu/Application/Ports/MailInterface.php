<?php

namespace App\Ukhu\Application\Ports;

interface MailInterface
{
    /**
     * Setup
     *
     * @return void
     */
    public function setup(): void;

    /**
     * Send email message
     *
     * @return bool
     */
    public function send(): bool;
}
