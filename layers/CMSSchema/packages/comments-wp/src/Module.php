<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentsWP;

use PoP\Root\Module\AbstractComponent;
use PoPCMSSchema\Users\Module as UsersComponent;

/**
 * Initialize component
 */
class Module extends AbstractComponent
{
    /**
     * All component classes that this component satisfies
     *
     * @return string[]
     */
    public function getSatisfiedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\Comments\Module::class,
        ];
    }

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\Comments\Module::class,
            \PoPCMSSchema\CustomPostsWP\Module::class,
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

        if (class_exists(UsersComponent::class)) {
            $this->initServices(
                dirname(__DIR__),
                '/ConditionalOnComponent/Users'
            );
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersComponent::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/Users'
            );
        }
    }
}
