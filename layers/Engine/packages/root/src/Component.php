<?php

declare(strict_types=1);

namespace PoP\Root;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Container\ContainerBuilderFactory;
use PoP\Root\Container\ServiceInstantiatorInterface;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [];
    }

    /**
     * Initialize services for the system container
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeSystemContainerServices(
        array $configuration = []
    ): void {
        parent::initializeSystemContainerServices($configuration);

        // Only after initializing the containerBuilder, can inject a service
        self::initYAMLSystemContainerServices(dirname(__DIR__));
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);

        // Only after initializing the containerBuilder, can inject a service
        self::initYAMLServices(dirname(__DIR__));
    }

    /**
     * Function called by the Bootloader after all components have been loaded
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        // Initialize container services through AutomaticallyInstantiatedServiceCompilerPass
        /**
         * @var ServiceInstantiatorInterface
         */
        $serviceInstantiator = ContainerBuilderFactory::getInstance()->get(ServiceInstantiatorInterface::class);
        $serviceInstantiator->initializeServices();
    }
}
