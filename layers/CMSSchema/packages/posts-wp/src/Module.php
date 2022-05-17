<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostsWP;

use PoP\Root\App;
use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\Posts\Module as PostsModule;
use PoPCMSSchema\Posts\ModuleConfiguration as PostsModuleConfiguration;

/**
 * Initialize component
 */
class Module extends AbstractModule
{
    /**
     * All component classes that this component satisfies
     *
     * @return string[]
     */
    public function getSatisfiedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\Posts\Module::class,
        ];
    }

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\Posts\Module::class,
            \PoPCMSSchema\CustomPostsWP\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        /** @var PostsModuleConfiguration */
        $moduleConfiguration = App::getComponent(PostsModule::class)->getConfiguration();
        if ($moduleConfiguration->addPostTypeToCustomPostUnionTypes()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/AddPostTypeToCustomPostUnionTypes/Overrides');
        }
    }
}
