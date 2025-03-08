<?php

declare(strict_types=1);

namespace PoPWPSchema\PageBuilder;

use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoP\ComponentModel\App;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ModuleInterface;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPWPSchema\CustomPosts\Module::class,
        ];
    }

    /**
     * Initialize services for the system container
     */
    protected function initializeSystemContainerServices(): void
    {
        $this->initSystemServices(dirname(__DIR__));
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
