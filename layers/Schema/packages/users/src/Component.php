<?php

declare(strict_types=1);

namespace PoPSchema\Users;

use PoP\Engine\App;
use PoP\Root\Managers\ComponentManager;
use PoP\API\Component as APIComponent;
use PoP\RESTAPI\Component as RESTAPIComponent;
use PoP\BasicService\Component\AbstractComponent;
use PoPSchema\CustomPosts\Component as CustomPostsComponent;

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
            \PoPSchema\QueriedObject\Component::class,
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
            \PoPSchema\CustomPosts\Component::class,
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
        $this->initServices(dirname(__DIR__));
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        if (class_exists(APIComponent::class) && App::getComponentManager()->getComponent(APIComponent::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/API');
        }
        if (class_exists(RESTAPIComponent::class) && App::getComponentManager()->getComponent(RESTAPIComponent::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/RESTAPI');
        }

        if (class_exists(CustomPostsComponent::class)) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/CustomPosts');
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(\PoPSchema\CustomPosts\Component::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/CustomPosts'
            );
            if (class_exists(APIComponent::class) && App::getComponentManager()->getComponent(APIComponent::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/CustomPosts/ConditionalOnComponent/API');
            }
            if (class_exists(RESTAPIComponent::class) && App::getComponentManager()->getComponent(RESTAPIComponent::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/CustomPosts/ConditionalOnComponent/RESTAPI');
            }
        }
    }
}
