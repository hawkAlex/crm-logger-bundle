<?php

namespace Tvision\CrmLoggerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Tvision\CrmLoggerBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('crm_logger');

        $rootNode
            ->children()
                ->booleanNode('is_logger_active')->defaultTrue()->end()
            ->end()
        ;

        return $treeBuilder;
    }
}