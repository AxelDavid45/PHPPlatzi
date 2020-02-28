<?php


namespace App\Commands;


use App\Models\Messages;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Email;

class SendEmailCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:sendEmail';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Verify if there is any message unsent
        $message = Messages::where('sent', false)->first();

        if ($message) {
            //Create the transport smtp
            $transport = new EsmtpTransport(getenv('SMTP_HOST'), getenv('SMTP_PORT'));
            $transport->setUsername(getenv('SMTP_USER'));
            $transport->setPassword(getenv('SMTP_PASS'));

            //The handler for the mail
            $mailer = new Mailer($transport);

            //The email
            $email = new Email();
            $email->from($message->email)
                ->to('you@example.com')
                ->subject('A new person contacted you via your website')
                ->text(
                    'Name:' . $message->name . ' Email: ' . $message->email
                    . ' Message: ' . $message->message
                );

            try {
                $mailer->send($email);
                $message->sent = true;
                $message->save();
                $output->writeln('Message sent successfully');
            } catch (TransportExceptionInterface $e) {
                $output->writeln($e->getMessage());
            }
        } else {
            $output->writeln("There is not any message to send");
        }
        return 0;
    }

}