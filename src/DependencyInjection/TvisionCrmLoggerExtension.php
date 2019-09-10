<?php
namespace Tvision\CrmLoggerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * Class TvisionCrmLoggerExtension
 * @package Tvision\CrmLoggerBundle\DependencyInjection
 */
class TvisionCrmLoggerExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     * @return mixed
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $definition = $container->getDefinition('tvision_feedback_crm.logs');

        $objects = $definition->getArguments();

        $definition->setArguments(
            array_merge($objects, [3 => $config['is_logger_active']])
        );

        $container->setParameter('crm_logger.is_logger_active', $config['is_logger_active']);
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return 'crm_logger';
    }
}