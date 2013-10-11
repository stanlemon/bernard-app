<?php
namespace Lemon\BernardBundle\Command;

use Lemon\BernardBundle\Message\CommandMessageHandler;

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

class ConsumeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bernard:consume')
            ->setDescription('Bernard queue consumer')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');

        $driver = new DoctrineDriver($connection);

        $queues = new PersistentFactory($driver, new SimpleSerializer);

        $middleware = new Middleware\MiddlewareBuilder;
        $middleware->push(new Middleware\ErrorLogFactory);
        $middleware->push(new Middleware\FailuresFactory(
            new PersistentFactory($driver, new SimpleSerializer)
        ));

        $consumer = new Consumer(
            new SimpleRouter(array(
                'ExecuteCommand' => new CommandMessageHandler($this->getApplication()),
            )),
            $middleware
        );

        $consumer->consume(
            $queues->create('execute-command')
        );
    }
}