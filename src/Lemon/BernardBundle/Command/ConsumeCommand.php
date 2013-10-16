<?php
namespace Lemon\BernardBundle\Command;

use Lemon\BernardBundle\Message\CommandMessageHandler;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

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

            $processes = array();

            foreach ($queues->all() as $name => $queue) {
                $command = 'nohup php '. $kernel->getRootDir() .'/console ' . $this->getName() .
                    ' --env='. $kernel->getEnvironment() .' ' . 
                    $name
                ;

                $this->getContainer()->get('logger')->debug(sprintf("Init consumer for %s queue", $name));
                $this->getContainer()->get('logger')->debug(sprintf("Command %s", $command));

                $processes[$name] = new Process($command);
            }

            // See: http://symfony.com/blog/new-in-symfony-2-2-process-component-enhancements
            while (count($processes) > 0) {
                 foreach ($processes as $name => $process) {
                     if (!$process->isStarted()) {
                         $this->getContainer()->get('logger')->debug(sprintf("Starting %s", $name));
                         $process->start();
                         continue;
                     }

                     $out = $process->getIncrementalOutput();
                     
                     if (!empty($out)) {
                         $output->write($out);
                     }

                     $error = $process->getIncrementalErrorOutput();

                     if (!empty($error)) {
                         $output->write($error);
                     }

                     if (!$process->isRunning()) {
                         $this->getContainer()->get('logger')->debug(sprintf("Stopping %s", $name));
                         unset($processes[$name]);
                     }
                 }

                 sleep(1);
            }
        }
    }
}