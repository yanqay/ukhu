<?php

declare(strict_types=1);

namespace App\Auth\Domain\Entities;

use App\Ukhu\Infrastructure\Adapters\Uuid;
use Assert\Assertion;

class User
{
    private $uuid;
    private $email;
    private $password;
    private $firstname;
    private $lastname;
    private $avatar;

    function __construct(
        string $uuid,
        string $email,
        string $password
    ) {
        Uuid::assertIsValidStringUuid($uuid);
        Assertion::email($email);

        $this->uuid = $uuid;
        $this->email = $email;
        $this->password = $password;
    }

    public function uuid()
    {
        return $this->uuid;
    }

    public function email()
    {
        return $this->email;
    }

    public function password()
    {
        return $this->password;
    }

    public function firstname()
    {
        return $this->firstname;
    }

    public function avatar()
    {
        return $this->avatar;
    }

    public function lastname()
    {
        return $this->lastname;
    }

    public function fullname()
    {
        if ($this->lastname) {
            return $this->firstname . ' ' . $this->lastname;
        }
        return $this->firstname;
    }

    public function setEmail($newEmail)
    {
        $this->email = $newEmail;
    }

    public function setFirstname($newFirstName)
    {
        $this->firstname = $newFirstName;
    }

    public function setLastname($newLastName)
    {
        $this->lastname = $newLastName;
    }

    public function setAvatar($newAvatar)
    {
        $this->avatar = $newAvatar;
    }
}