<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesACL;

use PoP\Root\App;
use PoP\BasicService\Component\AbstractComponent;
use PoPSchema\UserRolesAccessControl\Component as UserRolesAccessControlComponent;

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
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\UserRolesAccessControl\Component::class,
        ];
    }

    public function isEnabled(): bool
    {
        return App::getComponent(UserRolesAccessControlComponent::class)->isEnabled();
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        $this->initSystemServices(dirname(__DIR__));
    }
}
