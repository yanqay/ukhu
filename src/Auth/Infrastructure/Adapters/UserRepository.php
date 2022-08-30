<?php

namespace App\Auth\Infrastructure\Adapters;

use App\Auth\Domain\Entities\User;
use App\Auth\Domain\Exceptions\UserNotFound;
use App\Auth\Infrastructure\Adapters\Password;
use App\Ukhu\Infrastructure\Adapters\Uuid;
use App\Ukhu\Infrastructure\Adapters\DTOMapper;
use PDO;

class UserRepository
{
    private $db;
    private $DTOMapper;

    public function __construct(PDO $pdo, DTOMapper $DTOMapper)
    {
        $this->db = $pdo;
        $this->DTOMapper = $DTOMapper;
    }

    public function insert(User $user)
    {
        $uuid = $user->uuid();
        $email = $user->email();
        $password = Password::hashPassword($user->password());
        $created_by = $uuid;
        $created_at = $this->dateTimeInUTC('now', 'UTC', 'Y-m-d H:i:s');

        $sql = <<<SQL
            INSERT INTO users (`uuid`, `email`, `password`, `created_by`, `created_at`)
            VALUES (
                UNHEX(REPLACE(?, "-","")),
                ?,
                ?,
                UNHEX(REPLACE(?, "-","")),
                ?)
        SQL;
        $params = array($uuid, $email, $password, $created_by, $created_at);
        $sth = $this->db->prepare($sql);

        // log on failure
        if(!$sth->execute($params)){
            $this->log->error('sql_error', [$sth->debugDumpParams()]);

            // TODO throw internal server error
            throw new \Exception("Internal Server Error");
        }
    }

    /**
     * find user by email
     *
     * @param string $email
     * @return \App\Auth\Domain\Entities\User
     */
    public function findByEmail($email)
    {
        $sql = <<<SQL
            SELECT uuid, email, password, created_by
            FROM users
            WHERE email = :email
        SQL;
        $params = array(
            'email' => $email
        );
        $sth = $this->db->prepare($sql);
        $sth->execute($params);
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if(empty($result)){
            throw new UserNotFound();
        }

        // format result
        $result['uuid'] = Uuid::binaryToStringUUID($result['uuid']);
        $result['created_by'] = Uuid::binaryToStringUUID($result['created_by']);

        return $this->DTOMapper->mapArrayToClass($result, User::class);
    }

    /**
     * find user by id
     *
     * @param string $uuid
     * @throws \App\Auth\Domain\Exceptions\UserNotFound
     * @return \App\Auth\Domain\Entities\User
     */
    public function findById(string $uuid) : User
    {
        $sql = <<<SQL
            SELECT *
            FROM users
            WHERE uuid = UNHEX(REPLACE(?, "-",""))
        SQL;
        $params = array($uuid);
        $sth = $this->db->prepare($sql);
        $sth->execute($params);
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if(empty($result)){
            throw new UserNotFound();
        }

        // format result
        $result['uuid'] = Uuid::binaryToStringUUID($result['uuid']);
        $result['created_by'] = Uuid::binaryToStringUUID($result['created_by']);

        return $this->DTOMapper->mapArrayToClass($result, User::class);
    }


    public function emailIsTaken($email)
    {
        $sql = <<<SQL
            SELECT email
            FROM users
            WHERE email = :email
        SQL;
        $params = array(
            'email' => $email
        );
        $sth = $this->db->prepare($sql);
        $sth->execute($params);
        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if(!empty($result)){
            return true;
        }

        return false;
    }

    function dateTimeInUTC(string $datetime = 'now', string $timezone = 'UTC', string $responseFormat = 'Y-m-d H:i:s'): string
    {
        $nowInUTC = new \DateTime($datetime, new \DateTimeZone($timezone));
        return $nowInUTC->format($responseFormat);
    }
}
