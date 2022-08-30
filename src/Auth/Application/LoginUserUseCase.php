<?php

declare(strict_types=1);

namespace App\Auth\Application;

use App\Auth\Infrastructure\Adapters\Password;
use App\Auth\Infrastructure\Adapters\UserRepository;
use App\Ukhu\Infrastructure\Adapters\Session;
use Assert\Assertion;

class LoginUserUseCase
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \App\Auth\Domain\Exceptions\UserNotFound
     * @throws \App\Auth\Domain\Exceptions\IncorrectUserPassword
     */
    public function handle($session, string $email, string $password, string $csrf_token): bool
    {
        Assertion::email($email);
        try {
            $user = $this->userRepository->findByEmail($email);
        } catch (\Throwable $th) {
            throw new \App\Auth\Domain\Exceptions\UserNotFound();
        }

        if (Password::hashPassword($password) !== $user->password()) {
            throw new \App\Auth\Domain\Exceptions\IncorrectUserPassword();
        }

        // validate csrf_token
        $currentCSRFSession = $session->get(Session::CSRF_SESSION);
        if (!hash_equals($currentCSRFSession['token'], $csrf_token)){
            throw new \App\Auth\Domain\Exceptions\InvalidCSRFToken();
        }

        // set session for logged in user
        $session->set(Session::USER_SESSION, array(
            'user' => array(
                'uuid' => $user->uuid(),
                'email' => $user->email()
            )
        ));

        return true;
    }
}
