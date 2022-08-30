<?php

namespace App\Auth\Infrastructure\Http;

use App\Auth\Infrastructure\Adapters\UserRepository;
use App\Ukhu\Application\Ports\TemplateInterface;
use App\Ukhu\Infrastructure\Adapters\Session;
use App\Ukhu\Infrastructure\Http\Controller;
use Psr\Http\Message\ServerRequestInterface;
use App\Ukhu\Infrastructure\Adapters\Uuid;

class SettingsController extends Controller
{
    private $currentSession = null;
    private $userRepository;

    public function __construct(Session $session, TemplateInterface $template, UserRepository $userRepository)
    {
        $this->currentSession = $session;
        parent::__construct($template);
        $this->userRepository = $userRepository;
    }
    public function settings(ServerRequestInterface $request)
    {
        $userSession = $this->currentSession->getUserSession($request);
        Uuid::assertIsValidStringUuid($userSession['user']['uuid']);

        $user = $this->userRepository->findById($userSession['user']['uuid']);

        $user->user_letter = !$user->avatar()? strtoupper(substr($user->firstname(), 0, 1)) : null;

        $data = array(
            'user' => $user
        );

        return $this->view('settings.html', $data);
    }
}
