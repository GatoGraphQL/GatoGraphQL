<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostMutations;

use PoP\Root\App;
use PoPAPI\API\Module as APIModule;
use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\Users\Module as UsersModule;

/**
 * Initialize component
 */
class Module extends AbstractModule
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPostMutations\Module::class,
            \PoPCMSSchema\Posts\Module::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoPAPI\API\Module::class,
            \PoPCMSSchema\Users\Module::class,
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
        if (class_exists(APIModule::class) && App::getComponent(APIModule::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/API');
        }
        if (class_exists(UsersModule::class)) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersModule::class, $skipSchemaComponentClasses),
                '/ConditionalOnComponent/Users'
            );
        }
    }
}
