<?php

namespace Lemon\BernardBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LemonBernardExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');


        // Configure driver
        switch ($config['driver']) {
            case 'dbal':
                $connectionName = sprintf("doctrine.dbal.%s_connection", $config['dbal']);

                $container->getDefinition('bernard.doctrine_driver')
                    ->setArguments(array(new Reference($connectionName)));

                break;
            case 'sqs':
            case 'redis':
            case 'predis':
            case 'ironmq':
            case 'appengine':
            default:
                throw new \RuntimeException("The JMS serializer has not yet been implemented");
        }


        // Configure serializer
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
