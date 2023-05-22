<?php

declare(strict_types=1);

namespace PoP\Engine;

use PoP\CacheControl\Module as CacheControlModule;
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
            \PoP\ComponentModel\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            CacheControlModule::class,
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
        $this->initServices(dirname(__DIR__), '/Overrides');
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        // Conditional packages
        try {
            if (class_exists(CacheControlModule::class) && App::getModule(CacheControlModule::class)->isEnabled()) {
                $this->initServices(dirname(__DIR__), '/ConditionalOnModule/CacheControl');
                $this->initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(CacheControlModule::class, $skipSchemaModuleClasses),
                    '/ConditionalOnModule/CacheControl'
                );
            }
        } catch (ComponentNotExistsException) {
        }
    }
}
