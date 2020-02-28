<?php


namespace App\Commands;


use Exception;
use Respect\Validation\Validator as v;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Models\User;
use Illuminate\Database\Capsule\Manager as Capsule; //Eloquent

class CreateUserCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:createUser';

    public function configure()
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Email');
        $this->addArgument('password', InputArgument::REQUIRED, 'Password');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Fields needed with validation
        $dataValidator = v::key('email', v::email()->stringType()->notEmpty())
            ->key('password', v::stringType()->notEmpty());

        $data = [
            'email' => $input->getArgument('email'),
            'password' => $input->getArgument('password')
        ];

        try {
            // Validate data
            $dataValidator->assert($data);

            //Create the user
            $user = new User();
            $user->email = $data['email'];
            $hash_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $user->password = $hash_password;
            $user->save();

            $saved = true;

        } catch (Exception $e) {
            $saved = false;
            $output->writeln($e->getMessage());
        }
        if ($saved) {
            $output->writeln("User created successfully");
        } else {
            $output->writeln("User can not be created");
        }

        return 0;
    }

}