<?php

declare(strict_types=1);

namespace PoPSchema\Posts;

use PoP\Root\App;
use PoP\API\Component as APIComponent;
use PoP\RESTAPI\Component as RESTAPIComponent;
use PoP\BasicService\Component\AbstractComponent;
use PoPSchema\Users\Component as UsersComponent;

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
            \PoPSchema\CustomPosts\Component::class,
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
            \PoPSchema\Users\Component::class,
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

        if (class_exists(UsersComponent::class)) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersComponent::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/Users'
            );
            if (class_exists(APIComponent::class) && App::getComponent(APIComponent::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/Users/ConditionalOnComponent/API');
            }
            if (class_exists(RESTAPIComponent::class) && App::getComponent(RESTAPIComponent::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/Users/ConditionalOnComponent/RESTAPI');
            }
        }

        /** @var ComponentConfiguration */
        $componentConfiguration = $this->getConfiguration();
        if ($componentConfiguration->addPostTypeToCustomPostUnionTypes()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/AddPostTypeToCustomPostUnionTypes');
        }
    }
}
