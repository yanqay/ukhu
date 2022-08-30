<?php

namespace App\Auth\Infrastructure\Http;

use App\Auth\Application\RegisterUserUseCase;
use App\Auth\Domain\Exceptions\EmailAlreadyRegistered;
use App\Ukhu\Domain\Exceptions\InternalError;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class RegisterUserController
{
    private $registerUserUseCase;
    private $log;

    public function __construct(RegisterUserUseCase $registerUserUseCase, LoggerInterface $log)
    {
        $this->log = $log;
        $this->registerUserUseCase = $registerUserUseCase;
    }

    public function handle(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();

        try {
            $userId = $this->registerUserUseCase->handle($params['email']);

            $data = array(
                'user_id' => $userId,
                'title' => 'User registered succesfully'
            );
            return new JsonResponse($data, 200);
        } catch (EmailAlreadyRegistered $e) {
            $data = array();
            $data['error'] = array(
                'status' => 409,
                'title' => 'Email already taken.'
            );
            return new JsonResponse($data, 409);
        } catch (InternalError | \Exception $e) {
            $this->log->info($e->getMessage());

            $data = array();
            $data['error'] = array(
                'status' => 500,
                'title' => 'Internal Server Error'
            );
            return new JsonResponse($data, 500);
        }
    }
}
