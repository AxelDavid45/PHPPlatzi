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


        /*
                //Create the transport smtp
                $transport = new EsmtpTransport(getenv('SMTP_HOST'), getenv('SMTP_PORT'));
                $transport->setUsername(getenv('SMTP_USER'));
                $transport->setPassword(getenv('SMTP_PASS'));

                //The handler for the mail
                $mailer = new Mailer($transport);

                //The email
                $email = new Email();
                $email->from($requestData['email'])
                    ->to('you@example.com')
                    ->subject('A new person contacted you via your website')
                    ->text(
                        'Name:'. $requestData['name'].' Email: '.$requestData['email']
                    .' Message: '.$requestData['message']
                    );

                $mailer->send($email);*/
        return new RedirectResponse('/contact');
    }

}