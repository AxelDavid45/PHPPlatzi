<?php


namespace App\Controllers;


use Laminas\Diactoros\ServerRequest;

class ContactController extends BaseController
{
    public function index()
    {
        return $this->renderHTML('contact/form.twig');
    }

    public function sendForm(ServerRequest $request)
    {
        var_dump($request->getParsedBody());
        die;

    }

}