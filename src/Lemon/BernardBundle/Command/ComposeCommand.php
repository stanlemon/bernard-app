<?php
namespace Lemon\BernardBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\OutputInterface;

class ComposeCommand extends Command
{
    protected function configure()
    {
        $this->ignoreValidationErrors();
        $this
            ->setName('demo:compose')
            ->setDescription('Compose a command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Removed the proxy command from the argv list
        $argv = array_slice($_SERVER['argv'], 0, 1) + array_slice($_SERVER['argv'], 1);

        $input = new ArgvInput($argv);

        $name = current(array_slice($argv, 1, 1));

        $command = $this->getApplication()->find($name);

        return $command->run($input, $output);
    }
}
