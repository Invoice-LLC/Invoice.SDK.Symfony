<?php


namespace invoice\payment\DependencyInjection;;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('invoice_bundle');

        $rootNode
            ->children()
                ->scalarNode("api_key")
                    ->isRequired()
                ->end()
                ->scalarNode("login")
                    ->isRequired()
                ->end();

        return $treeBuilder;
    }
}