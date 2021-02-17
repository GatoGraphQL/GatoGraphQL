<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesACL;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
use PoPSchema\UserRolesAccessControl\Component as UserRolesAccessControlComponent;
use PoPSchema\UserRolesACL\Container\CompilerPasses\ConfigureAccessControlCompilerPass;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use CanDisableComponentTrait;

    // const VERSION = '0.1.0';

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\UserRolesAccessControl\Component::class,
        ];
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
        if (self::isEnabled()) {
            parent::initializeContainerServices($configuration, $skipSchema, $skipSchemaComponentClasses);
            self::initYAMLServices(dirname(__DIR__));
        }
    }

    protected static function resolveEnabled()
    {
        return UserRolesAccessControlComponent::isEnabled();
    }

    /**
     * Get all the compiler pass classes required to register on the container
     *
     * @return string[]
     */
    public static function getContainerCompilerPassClasses(): array
    {
        return [
            ConfigureAccessControlCompilerPass::class,
        ];
    }
}
