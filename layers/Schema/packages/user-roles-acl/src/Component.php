<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesACL;

use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;
use PoPSchema\UserRolesAccessControl\Component as UserRolesAccessControlComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    use CanDisableComponentTrait;

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

    protected static function resolveEnabled()
    {
        return UserRolesAccessControlComponent::isEnabled();
    }

    /**
     * Initialize services for the system container
     */
    protected static function initializeSystemContainerServices(): void
    {
        if (self::isEnabled()) {
            parent::initializeSystemContainerServices();
            self::initYAMLSystemContainerServices(dirname(__DIR__));
        }
    }
}
