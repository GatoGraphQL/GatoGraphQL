<?php

declare(strict_types=1);

namespace PoPWPSchema\BlockContentParser;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoP\DOMCrawler\Module::class,
            \PoP\EngineWP\Module::class,
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
    }
}
