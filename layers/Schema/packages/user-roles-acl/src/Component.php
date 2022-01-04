<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesACL;

use PoP\Root\Managers\ComponentManager;
use PoP\BasicService\Component\AbstractComponent;
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
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPSchema\UserRolesAccessControl\Component::class,
        ];
    }

    protected function resolveEnabled(): bool
    {
        return ComponentManager::getComponent(UserRolesAccessControlComponent::class)->isEnabled();
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        if ($this->isEnabled()) {
                $this->initSystemServices(dirname(__DIR__));
        }
    }
}
