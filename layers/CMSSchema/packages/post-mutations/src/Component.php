<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations;

use PoP\Root\App;
use PoPAPI\API\Component as APIComponent;
use PoP\Root\Component\AbstractComponent;
use PoPCMSSchema\Users\Component as UsersComponent;

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
            \PoPCMSSchema\CustomPostMutations\Component::class,
            \PoPCMSSchema\Posts\Component::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoPAPI\API\Component::class,
            \PoPCMSSchema\Users\Component::class,
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
        if (class_exists(UsersComponent::class)) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersComponent::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/Users'
            );
        }
    }
}
