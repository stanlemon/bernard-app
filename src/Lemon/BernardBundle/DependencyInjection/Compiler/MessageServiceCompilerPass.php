<?php
namespace Lemon\BernardBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class MessageServiceCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition(
            'bernard.router'
        );

        $taggedServices = $container->findTaggedServiceIds(
            'bernard.receiver'
        );

        $receivers = array();

        foreach ($taggedServices as $id => $attributes) {
            $service = $container->getDefinition($id);

            $reflection = new \ReflectionClass($service->getClass());

            $receivers[$reflection->getShortName()] = new Reference($id);
        }

        $definition->setArguments(array($receivers));
    }
}
