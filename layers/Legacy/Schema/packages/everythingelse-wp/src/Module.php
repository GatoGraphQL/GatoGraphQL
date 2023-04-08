<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElseWP;

use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoP\Root\App;
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
            \PoPSchema\EverythingElse\Module::class,
            \PoP\EngineWP\Module::class,
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
     * @param array<string,mixed> $configuration
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
		try {
            if (class_exists(CustomPostsModule::class) && App::getModule(CustomPostsModule::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnModule/CustomPosts');
            }
        } catch (ComponentNotExistsException) {
        }
    }
}
