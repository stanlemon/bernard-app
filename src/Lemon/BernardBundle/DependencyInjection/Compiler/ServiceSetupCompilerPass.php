<?php
namespace Lemon\BernardBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ServiceSetupCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('lemon_bernard.config');

        switch ($config['driver']) {
            case 'dbal':
                $connectionName = sprintf("doctrine.dbal.%s_connection", $config['dbal']);
                
                if (!$container->hasDefinition($connectionName)) {
                    throw new \RuntimeException(sprintf("DBAL connection %s does not exist", $config['dbal']));
                }

                $container->getDefinition('bernard.doctrine_driver')
                    ->setArguments(array(new Reference($connectionName)));

                break;
            default:
                throw new \RuntimeException("The JMS serializer has not yet been implemented");
        }

        switch ($config['serializer']) {
            case 'simple':
                $serializer = 'bernard.simple_serializer';
                break;
            case 'jms':
                throw new \RuntimeException("The JMS serializer has not yet been implemented");
            case 'symfony':
                $serializer = 'bernard.symfony_serializer';
                break;
            default:
                throw new \RuntimeException("Invalid serializer specified");
        }

        $container->setAlias('bernard.serializer', $serializer);
    }
}
