<?php
namespace Lemon\BernardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Bernard\Consumer;
use Bernard\Message;
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
        $this->ignoreValidationErrors();

        $this
            ->setName('bernard:produce')
            ->setDescription('Bernard queue producer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');

        $driver = new DoctrineDriver($connection);

        $producer = new Producer(
            new PersistentFactory($driver, new SimpleSerializer), 
            new Middleware\MiddlewareBuilder
        );

        $argv = array_slice($_SERVER['argv'], 0, 1) + array_slice($_SERVER['argv'], 1);

        $producer->produce(new Message\DefaultMessage('ExecuteCommand', array(
            "command" => $argv,
        )));
    }
}
