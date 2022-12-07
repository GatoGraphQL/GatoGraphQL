<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\App;
use PoPAPI\API\Module as APIModule;
use PoPAPI\RESTAPI\Module as RESTAPIModule;
use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\Comments\Module as CommentsModule;
use PoPCMSSchema\Users\Module as UsersModule;

class Module extends AbstractModule
{
    protected function requiresSatisfyingModule(): bool
    {
        return true;
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPosts\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            \PoPAPI\API\Module::class,
            \PoPAPI\RESTAPI\Module::class,
            \PoPCMSSchema\Comments\Module::class,
            \PoPCMSSchema\Users\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
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
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersModule::class, $skipSchemaModuleClasses),
                '/ConditionalOnModule/Users'
            );
            if (class_exists(APIModule::class) && App::getModule(APIModule::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnModule/Users/ConditionalOnModule/API');
            }
            if (class_exists(RESTAPIModule::class) && App::getModule(RESTAPIModule::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnModule/Users/ConditionalOnModule/RESTAPI');
            }
        }

        if (class_exists(CommentsModule::class) && App::getModule(CommentsModule::class)->isEnabled()) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(CommentsModule::class, $skipSchemaModuleClasses),
                '/ConditionalOnModule/Comments'
            );
        }
    }
}
