<?php

declare(strict_types=1);

namespace PoP\RESTAPI;

use PoP\Engine\App;
use PoP\Root\Managers\ComponentManager;
use PoP\API\Component as APIComponent;
use PoP\BasicService\Component\AbstractComponent;
use PoP\Root\Component\CanDisableComponentTrait;

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
            \PoP\APIMirrorQuery\Component::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        if ($this->isEnabled()) {
            $this->initServices(dirname(__DIR__));
        }
    }

    protected function resolveEnabled(): bool
    {
        return App::getComponentManager()->getComponent(APIComponent::class)->isEnabled() && !Environment::disableRESTAPI();
    }
}
