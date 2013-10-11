<?php
namespace Lemon\BernardBundle\Message;

use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Input\ArgvInput;
use Bernard\Message\DefaultMessage;

class CommandMessageHandler
{
    public function __construct($application)
    {
        $this->application = $application;
    }

    public function executeCommand(DefaultMessage $message)
    {
        $argv = $message->command;

        if (is_string($argv)) {
            $input = new StringInput($argv);

            $name = trim(substr($argv, 0, strpos($argv, ' ')));
        } else {
            $input = new ArgvInput($argv);
            
            $name = current(array_slice($argv, 1));
        }

        $command = $this->application->find($name);

        $command->run($input, $output = new ConsoleOutput());
    }
}