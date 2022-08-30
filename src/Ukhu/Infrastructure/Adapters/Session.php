<?php

namespace App\Ukhu\Infrastructure\Adapters;

use App\Ukhu\Application\Adapters\PSR7Sessions;
use Laminas\Diactoros\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class Session
{
    const USER_SESSION = 'user_session';
    const CSRF_SESSION = 'csrf_session';
    const TEMPLATE_SESSION = 'session';

    /**
     * gets current session from request
     *
     * @param ServerRequest $request
     * @return array|null $currentSession
     */
    public function getUserSession(ServerRequestInterface $request)
    {
        $session = self::getSessionFromRequest($request);

        if ($session) {
            $currentSession = $session->get(Session::USER_SESSION);
            if (!empty($currentSession) && is_array($currentSession)) {
                return $currentSession;
            }
        }
        return null;
    }

    public static function clearSession(ServerRequestInterface $request)
    {
        $session = self::getSessionFromRequest($request);

        if ($session) {
            $session->set(Session::USER_SESSION, array());
        }
    }

    /**
     * gets current session
     *
     * @param ServerRequest $request
     * @return PSR7Sessions\Storageless\Session\LazySession
     */
    public static function getSessionFromRequest(ServerRequestInterface $request)
    {
        return $request->getAttribute(\PSR7Sessions\Storageless\Http\SessionMiddleware::SESSION_ATTRIBUTE);
    }
}
