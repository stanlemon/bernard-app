<?php
namespace Lemon\BernardBundle\Message;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Input\ArgvInput;
use Bernard\Message\DefaultMessage;

class CommandMessageHandler
{
    protected $kernel;
    protected $logger;

    public function setKernel($kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    public function commandMessageHandler(DefaultMessage $message)
    {
        $this->logger->info("Initiating command message");

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $argv = $message->command;

        if (is_string($argv)) {
            $input = new StringInput($argv);

            $name = trim(substr($argv, 0, strpos($argv, ' ')));

            $this->logger->info("Issuing... {$argv}");
        } else {
            $input = new ArgvInput($argv);
            
            $name = current(array_slice($argv, 1));

            $this->logger->info("Issuing... " . ((string) $input));
        }

        $output = new ConsoleOutput();

        $application->run($input, $output);

        $this->logger->info("Finished command message");
    }
}