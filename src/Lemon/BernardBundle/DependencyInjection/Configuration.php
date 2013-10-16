<?php

namespace Lemon\BernardBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('lemon_bernard');

        $rootNode
            ->children()
                ->enumNode('driver')
                    ->values(array('dbal', 'ironmq'))
                    ->defaultValue('dbal')
                ->end()
                ->scalarNode('serializer')->defaultValue('symfony')->end()
                ->scalarNode('dbal')->defaultValue('default')->end()
                ->arrayNode('ironmq')
                    ->children()
                        ->scalarNode('token')->end()
                        ->scalarNode('project')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
