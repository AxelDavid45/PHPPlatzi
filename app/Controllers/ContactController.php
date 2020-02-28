<?php


namespace App\Controllers;


use App\Models\Messages;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ServerRequest;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

class ContactController extends BaseController
{
    public function index()
    {
        return $this->renderHTML('contact/form.twig');
    }

    public function sendForm(ServerRequest $request)
    {
        $requestData = $request->getParsedBody();
        //Save the message in the db
        $message = new Messages();
        $message->email = $requestData['email'];
        $message->name = $requestData['name'];
        $message->message = $requestData['message'];
        $message->save();

        return new RedirectResponse('/contact');
    }

}