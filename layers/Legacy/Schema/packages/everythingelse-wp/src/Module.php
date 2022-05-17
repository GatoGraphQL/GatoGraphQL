<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElseWP;

use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;

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
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        if (class_exists(CustomPostsModule::class)) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnModule/CustomPosts');
        }
    }
}
