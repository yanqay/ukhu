<?php

namespace App\Auth\Infrastructure\Http;

use App\Auth\Application\LoginUserUseCase;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class LoginUserController
{
    private $loginUserUseCase;
    private $log;

    public function __construct(LoginUserUseCase $loginUserUseCase, LoggerInterface $log)
    {
        $this->loginUserUseCase = $loginUserUseCase;
        $this->log = $log;
    }

    public function handle(ServerRequestInterface $request)
    {
        $params = $request->getParsedBody();
        $session = $request->getAttribute(\PSR7Sessions\Storageless\Http\SessionMiddleware::SESSION_ATTRIBUTE);

        try {
            $response = $this->loginUserUseCase->handle(
                $session,
                $params['email'],
                $params['password'],
                $params['csrf_token']
            );

            return new JsonResponse([
                'data' => $response
            ], 200);
        } catch (\App\Auth\Domain\Exceptions\UserNotFound $e) {
            $data = array();
            $data['error'] = array(
                'status' => 404,
                'title' => 'User not found.'
            );
            return new JsonResponse($data, 404);
        } catch (\App\Auth\Domain\Exceptions\IncorrectUserPassword $e) {
            $data = array();
            $data['error'] = array(
                'status' => 403,
                'title' => 'Incorrect Password.'
            );
            return new JsonResponse($data, 403);
        } catch (\Exception $e) {
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
