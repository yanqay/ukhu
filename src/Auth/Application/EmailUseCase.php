<?php

namespace App\Auth\Application;

use App\Auth\Domain\Entities\User;
use App\Ukhu\Domain\Exceptions\InternalError;
use App\Ukhu\Infrastructure\Adapters\Mailer;

class EmailUseCase
{
    private $mailer;
    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function welcomeNewUserAccount(User $user) : void
    {
        $this->mailer->configUserData(
            array(
                'subject' => 'User Registration',
                'altBody' => 'User Registered Succesfully',
                'toEmail' => $user->email(),
                'toName' => 'To ' . $user->fullname(),
            )
        );
        $this->mailer->configTemplate('email/new_user_created.html', array(
            'userEmail' => $user->email(),
            'content' => <<<CONTENT
                Thanks for your registration. Now you can start using Ukhu.
                <br>Here is your registration data:
                <ul>
                    <li>Email: {$user->email()}</li>
                    <li>Password: {$user->password()}</li>
                </ul>
                CONTENT
        ));
        if (!$this->mailer->send()) {
            throw new InternalError("Internal Server Error");
        }
    }
}
