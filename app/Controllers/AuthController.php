<?php


namespace App\Controllers;


use App\Models\User;
use Respect\Validation\Validator as v;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\ServerRequest;

class AuthController extends BaseController
{
    public function getLogin()
    {
        return $this->renderHTML('login.twig');
    }

    public function postLogin(ServerRequest $request) {
        $message = null;
        if ($request->getMethod() == 'POST') {
            //Parsed data
            $formData = $request->getParsedBody();
            //Search the user
            $user = User::where('email', $formData['email'])->first();

            if ($user) {
                //verify password
                $verifyPassword = password_verify($formData['password'], $user->password);
                if ($verifyPassword) {
                    $_SESSION['userId'] = true;
                    return new RedirectResponse('/admin');
                } else {
                    $message = 'Bad Credentials';
                }
            } else {
                $message = 'Bad Credentials';
            }

        }
        return $this->renderHTML('login.twig', [
           'message' => $message
        ]);
    }

    public function getDashboard()
    {
        return $this->renderHTML('admin.twig');
    }

    public function logout() {
        session_destroy();
        return new RedirectResponse('/auth');
    }

}