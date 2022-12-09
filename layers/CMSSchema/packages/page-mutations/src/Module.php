<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\App;
use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\CommentMutations\Module as CommentMutationsModule;

class Module extends AbstractModule
{
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
            \PoPCMSSchema\CommentMutations\Module::class,
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
        if (class_exists(CommentMutationsModule::class) && App::getModule(CommentMutationsModule::class)->isEnabled()) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(CommentMutationsModule::class, $skipSchemaModuleClasses),
                '/ConditionalOnModule/CommentMutations'
            );
        }
    }
}
