<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations;

use PoPAPI\API\Module as APIModule;
use PoPCMSSchema\CommentMutations\Module as CommentMutationsModule;
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
            \PoPCMSSchema\CustomPostMutations\Module::class,
            \PoPCMSSchema\Pages\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            APIModule::class,
            CommentMutationsModule::class,
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
            if (class_exists(UsersModule::class) && App::getModule(UsersModule::class)->isEnabled()) {
                $this->initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(UsersModule::class, $skipSchemaModuleClasses),
                    '/ConditionalOnModule/Users'
                );
            }
        } catch (ComponentNotExistsException) {
        }

        try {
            if (class_exists(CommentMutationsModule::class) && App::getModule(CommentMutationsModule::class)->isEnabled()) {
                $this->initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(CommentMutationsModule::class, $skipSchemaModuleClasses),
                    '/ConditionalOnModule/CommentMutations'
                );
            }
        } catch (ComponentNotExistsException) {
        }
    }
}
