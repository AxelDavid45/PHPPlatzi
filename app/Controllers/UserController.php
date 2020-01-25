<?php


namespace App\Controllers;

use Respect\Validation\Validator as v;
use App\Models\User;

class UserController extends BaseController
{
    public function create()
    {
        return $this->renderHTML('createUser.twig');
    }

    public function store($request) {
        $message = null;

        if ($request->getMethod() == 'POST') {
            $data = $request->getparsedBody();

            $dataValidator = v::key('email', v::email()->stringType()->notEmpty())
                ->key('password', v::stringType()->notEmpty());


            try {
                $dataValidator->assert($data);

                $user = new User();
                $user->email = $data['email'];
                $hash_password = password_hash($data['password'], PASSWORD_DEFAULT);
                $user->password = $hash_password;

                $user->save();
                $message = true;

            } catch (\Exception $e) {
                $message = $e->getMessage();
            }

        }
        var_dump($message);
        die();

        return $this->renderHTML('createUser.twig', [
            'message' => $message
        ]);
    }

}