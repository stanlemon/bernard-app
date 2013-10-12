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
use Bernard\Serializer\SymfonySerializer;
use Bernard\Driver\DoctrineDriver;
use Doctrine\DBAL\DriverManager;

class ConsumeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bernard:consume')
            ->setDescription('Bernard queue consumer')
            ->addArgument('queue', InputArgument::OPTIONAL, 'Name of queue that will be consumed.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getArgument('queue')) {
            $consumer = $this->getContainer()->get('bernard.consumer');

            $queues = $this->getContainer()->get('bernard.queue_factory');

            $consumer->consume($queues->create($input->getArgument('queue')));
        } else {
            $kernel = $this->getContainer()->get('kernel');

            $queues = $this->getContainer()->get('bernard.queue_factory');

            foreach ($queues->all() as $name => $queue) {
                $command = 'nohup php '. $kernel->getRootDir() .'/console ' . $this->getName() .
                    ' --env='. $kernel->getEnvironment() .' ' . 
                    $name . 
                    ' > /dev/null 2>&1 &';
                    
                $process = new \Symfony\Component\Process\Process($command);
                $process->run();
            }
        }
    }
}