<?php

namespace FOS\TwitterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration for the bundle
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fos_twitter');

        $rootNode
            ->children()
                ->scalarNode('file')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('consumer_key')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('consumer_secret')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('callback_url')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('anywhere_version')->defaultValue('1')->end()
                ->scalarNode('alias')->defaultNull()->end();

        return $treeBuilder;
    }

}

