<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostsWP;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\App;
use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\Posts\Module as PostsModule;
use PoPCMSSchema\Posts\ModuleConfiguration as PostsModuleConfiguration;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<\PoPCMSSchema\Posts\Module>>
     */
    public function getSatisfiedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Posts\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Posts\Module::class,
            \PoPCMSSchema\CustomPostsWP\Module::class,
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
        /** @var PostsModuleConfiguration */
        $moduleConfiguration = App::getModule(PostsModule::class)->getConfiguration();
        if ($moduleConfiguration->addPostTypeToCustomPostUnionTypes()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/AddPostTypeToCustomPostUnionTypes/Overrides');
        }
    }
}
