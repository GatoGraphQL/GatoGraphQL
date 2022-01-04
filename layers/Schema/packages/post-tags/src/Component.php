<?php

declare(strict_types=1);

namespace PoPSchema\PostTags;

use PoP\API\Component as APIComponent;
use PoP\RESTAPI\Component as RESTAPIComponent;
use PoP\Root\Component\AbstractComponent;

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
            \PoPSchema\Posts\Component::class,
            \PoPSchema\Tags\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoP\API\Component::class,
            \PoP\RESTAPI\Component::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        ComponentConfiguration::setConfiguration($configuration);
        $this->initServices(dirname(__DIR__));
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
        if (class_exists(APIComponent::class) && \PoP\Root\Managers\ComponentManager::getComponent(APIComponent::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/API');
        }
        if (class_exists(RESTAPIComponent::class) && \PoP\Root\Managers\ComponentManager::getComponent(RESTAPIComponent::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/RESTAPI');
        }
    }
}
