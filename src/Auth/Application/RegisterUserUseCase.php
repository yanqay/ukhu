<?php

namespace App\Auth\Application;

use App\Auth\Domain\Entities\User;
use App\Auth\Domain\Exceptions\EmailAlreadyRegistered;
use App\Auth\Infrastructure\Adapters\Password;
use App\Auth\Infrastructure\Adapters\UserRepository;
use App\Ukhu\Infrastructure\Adapters\Uuid;

class RegisterUserUseCase
{
    private $userRepository;
    private $emailUseCase;

    public function __construct(UserRepository $userRepository, EmailUseCase $emailUseCase)
    {
        $this->userRepository = $userRepository;
        $this->emailUseCase = $emailUseCase;
    }

    public function handle(string $email)
    {
        if ($this->userRepository->emailIsTaken($email)) {
            throw new EmailAlreadyRegistered();
        }

        $generatedPassword = (new Password)->get();
        $generatedId = Uuid::generateStringUuid();

        // store new user
        $user = new User(
            $generatedId,
            $email,
            $generatedPassword
        );
        $this->userRepository->insert($user);

        $this->emailUseCase->welcomeNewUserAccount($user);

        return $generatedId;
    }
}
