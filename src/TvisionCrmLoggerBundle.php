<?php


namespace Tvision\CrmLoggerBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tvision\CrmLoggerBundle\DependencyInjection\TvisionCrmLoggerExtension;

/**
 * Class TvisionCrmLoggerBundle
 * @package Tvision\CrmLoggerBundle
 */
class TvisionCrmLoggerBundle extends Bundle
{
    /**
     * @return null|ExtensionInterface
    */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new TvisionCrmLoggerExtension();
        }

        return $this->extension;
    }
}