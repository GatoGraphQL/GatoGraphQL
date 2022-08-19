<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagsWP;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<\PoPCMSSchema\PostTags\Module>>
     */
    public function getSatisfiedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\PostTags\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\PostTags\Module::class,
            \PoPCMSSchema\CustomPostTagsWP\Module::class,
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
