<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElseWP;

use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPSchema\EverythingElse\Module::class,
            \PoP\EngineWP\Module::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPosts\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        if (class_exists(CustomPostsModule::class)) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnModule/CustomPosts');
        }
    }
}
