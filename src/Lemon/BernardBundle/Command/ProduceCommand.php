<?php
namespace Lemon\BernardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Bernard\Consumer;
use Bernard\Message;
use Bernard\Message\DefaultMessage;
use Bernard\Middleware;
use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Router\SimpleRouter;
use Bernard\Serializer\SimpleSerializer;
use Bernard\Driver\DoctrineDriver;
use Doctrine\DBAL\DriverManager;

class ProduceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bernard:produce')
            ->setDescription('Bernard queue producer')
            ->addArgument('name', InputArgument::REQUIRED, 'Name for the message eg. "ImportUsers".')
            ->addArgument('message', InputArgument::OPTIONAL, 'JSON encoded string that is used for message properties.')
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $message = json_decode($input->getArgument('message'), true) ?: array();

        if (json_last_error()) {
            throw new \RuntimeException('Could not decode invalid JSON [' . json_last_error() . ']');
        }

        $producer = $this->getContainer()->get('bernard.producer');

        $producer->produce(new DefaultMessage($name, $message));
    }
}
