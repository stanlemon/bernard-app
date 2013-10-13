<?php
namespace Lemon\BernardBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class ServiceSetupCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('bernard.doctrine_driver');
        $arguments = $definition->getArguments();
        $connection = current($arguments);
        
        if (!$connection instanceof Reference || !$container->hasDefinition((string) $connection)) {
            throw new \RuntimeException(sprintf("DBAL connection %s does not exist", $config['dbal']));
        }
    }
}
