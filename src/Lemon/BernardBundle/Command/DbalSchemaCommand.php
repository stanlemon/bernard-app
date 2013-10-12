<?php
namespace Lemon\BernardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Bernard\Doctrine\MessagesSchema;
use Doctrine\DBAL\Schema\Schema;

class DbalSchemaCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bernard:dbal-schema')
            ->setDescription('Setup the dbal schema')
            ->addOption('drop', 'd', InputOption::VALUE_NONE, "Drop bernard's dbal schema")
            ->addOption('force', 'f', InputOption::VALUE_NONE, "Execute the dbal schema sql")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connection = $this->getContainer()->get('doctrine.dbal.default_connection');

        $messagesSchema = MessagesSchema::create($schema = new Schema);

        if ($input->getOption('drop')) {
            $sql = $schema->toDropSql($connection->getDatabasePlatform());
        } else {
            $sql = $schema->toSql($connection->getDatabasePlatform());
        }

        foreach ($sql as $query) {
            if ($input->getOption('force')) {
                $connection->exec($query);
            }

            $output->writeln($query);
        }
    }
}
