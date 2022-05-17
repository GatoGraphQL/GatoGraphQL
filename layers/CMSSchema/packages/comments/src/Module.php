<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments;

use PoP\Root\App;
use PoPAPI\API\Module as APIModule;
use PoPAPI\RESTAPI\Module as RESTAPIModule;
use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\Users\Module as UsersModule;

class Module extends AbstractModule
{
    protected function requiresSatisfyingModule(): bool
    {
        return true;
    }

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPosts\Module::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            \PoPAPI\API\Module::class,
            \PoPAPI\RESTAPI\Module::class,
            \PoPCMSSchema\Users\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        if (class_exists(APIModule::class) && App::getModule(APIModule::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnModule/API');
        }

        if (class_exists(RESTAPIModule::class) && App::getModule(RESTAPIModule::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnModule/RESTAPI');
        }

        if (class_exists(UsersModule::class)) {
            $this->initServices(
                dirname(__DIR__),
                '/ConditionalOnModule/Users'
            );
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersModule::class, $skipSchemaModuleClasses),
                '/ConditionalOnModule/Users'
            );
        }
    }
}
