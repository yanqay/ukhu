<?php

namespace App\Auth\Infrastructure\Http;

use App\Ukhu\Infrastructure\Adapters\Session;
use App\Ukhu\Infrastructure\Http\Controller;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    public function showLogin()
    {
        return $this->view('login.html');
    }

    public function logout(ServerRequestInterface $request)
    {
        Session::clearSession($request);

        // TODO use "/" as constant
        return new \Laminas\Diactoros\Response\RedirectResponse('/', 302);
    }

    //forgot controller
    public function sendResetLinkEmail()
    {
    }

    public function showLinkRequestForm()
    {
    }

    // reset controller
    public function reset()
    {
    }

    public function showResetForm()
    {
    }

    public function showRegistrationForm()
    {
        return $this->view('register.html');
    }
}
