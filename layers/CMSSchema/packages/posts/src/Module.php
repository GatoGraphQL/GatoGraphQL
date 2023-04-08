<?php

declare(strict_types=1);

namespace PoPCMSSchema\Posts;

use PoPAPI\API\Module as APIModule;
use PoPAPI\RESTAPI\Module as RESTAPIModule;
use PoPCMSSchema\Comments\Module as CommentsModule;
use PoPCMSSchema\CustomPostMedia\Module as CustomPostMediaModule;
use PoPCMSSchema\Users\Module as UsersModule;
use PoP\Root\App;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ModuleInterface;

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
            APIModule::class,
            RESTAPIModule::class,
            CommentsModule::class,
            CustomPostMediaModule::class,
            UsersModule::class,
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

        try {
            if (class_exists(APIModule::class) && App::getModule(APIModule::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnModule/API');
            }
        } catch (ComponentNotExistsException) {
        }

        try {
            if (class_exists(RESTAPIModule::class) && App::getModule(RESTAPIModule::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnModule/RESTAPI');
            }
        } catch (ComponentNotExistsException) {
        }

        try {
            if (class_exists(UsersModule::class) && App::getModule(UsersModule::class)->isEnabled()) {
                $this->initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(UsersModule::class, $skipSchemaModuleClasses),
                    '/ConditionalOnModule/Users'
                );

                try {
                    if (class_exists(APIModule::class) && App::getModule(APIModule::class)->isEnabled()) {
                        $this->initServices(dirname(__DIR__), '/ConditionalOnModule/Users/ConditionalOnModule/API');
                    }
                } catch (ComponentNotExistsException) {
                }

                try {
                    if (class_exists(RESTAPIModule::class) && App::getModule(RESTAPIModule::class)->isEnabled()) {
                        $this->initServices(dirname(__DIR__), '/ConditionalOnModule/Users/ConditionalOnModule/RESTAPI');
                    }
                } catch (ComponentNotExistsException) {
                }
            }
        } catch (ComponentNotExistsException) {
        }

        try {
            if (class_exists(CommentsModule::class) && App::getModule(CommentsModule::class)->isEnabled()) {
                $this->initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(CommentsModule::class, $skipSchemaModuleClasses),
                    '/ConditionalOnModule/Comments'
                );
            }
        } catch (ComponentNotExistsException) {
        }

        try {
            if (class_exists(CustomPostMediaModule::class) && App::getModule(CustomPostMediaModule::class)->isEnabled()) {
                $this->initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(CustomPostMediaModule::class, $skipSchemaModuleClasses),
                    '/ConditionalOnModule/CustomPostMedia'
                );
            }
        } catch (ComponentNotExistsException) {
        }
    }
}
