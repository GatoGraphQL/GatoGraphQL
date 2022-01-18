<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users;

use PoP\Root\App;
use PoPAPI\API\Component as APIComponent;
use PoPAPI\RESTAPI\Component as RESTAPIComponent;
use PoP\Root\Component\AbstractComponent;
use PoPCMSSchema\CustomPosts\Component as CustomPostsComponent;

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
            \PoPCMSSchema\QueriedObject\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoPAPI\API\Component::class,
            \PoPAPI\RESTAPI\Component::class,
            \PoPCMSSchema\CustomPosts\Component::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        if (class_exists(APIComponent::class) && App::getComponent(APIComponent::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/API');
        }
        if (class_exists(RESTAPIComponent::class) && App::getComponent(RESTAPIComponent::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/RESTAPI');
        }

        if (class_exists(CustomPostsComponent::class)) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/CustomPosts');
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(\PoPCMSSchema\CustomPosts\Component::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/CustomPosts'
            );
            if (class_exists(APIComponent::class) && App::getComponent(APIComponent::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/CustomPosts/ConditionalOnComponent/API');
            }
            if (class_exists(RESTAPIComponent::class) && App::getComponent(RESTAPIComponent::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/CustomPosts/ConditionalOnComponent/RESTAPI');
            }
        }
    }
}
