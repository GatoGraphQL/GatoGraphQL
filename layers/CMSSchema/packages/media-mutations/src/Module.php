<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations;

use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoP\ComponentModel\App;
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
            \PoPCMSSchema\Media\Module::class,
            \PoPCMSSchema\UserRoles\Module::class,
            \PoPCMSSchema\UserStateMutations\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            CustomPostsModule::class,
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
            if (class_exists(CustomPostsModule::class) && App::getModule(CustomPostsModule::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnModule/CustomPosts');
                $this->initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(CustomPostsModule::class, $skipSchemaModuleClasses),
                    '/ConditionalOnModule/CustomPosts'
                );
            }
        } catch (ComponentNotExistsException) {
        }
    }
}
