<?php
namespace Lemon\BernardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Bernard\Message\DefaultMessage;

class ComposeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->ignoreValidationErrors();
        $this
            ->setName('bernard:compose')
            ->setDescription('Compose a command message to bernard')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Removed the proxy command from the argv list
        $argv = array_slice($_SERVER['argv'], 0, 1) + array_slice($_SERVER['argv'], 1);

        $this->getContainer()->get('bernard.producer')->produce(new DefaultMessage('CommandMessageHandler', array(
            "command" => $argv,
        )));
    }
}
