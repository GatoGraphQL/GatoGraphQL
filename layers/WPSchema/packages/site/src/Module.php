<?php

declare(strict_types=1);

namespace PoPWPSchema\Site;

use PoPCMSSchema\Categories\Module as CategoriesModule;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;
use PoPCMSSchema\Tags\Module as TagsModule;
use PoP\ComponentModel\App;
use PoP\Root\Exception\ComponentNotExistsException;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ModuleInterface;

use function class_exists;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPWPSchema\SchemaCommons\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            CustomPostsModule::class,
            CategoriesModule::class,
            TagsModule::class,
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
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        /**
         * `_urlToEntity` resolves only the custom post types and
         * taxonomies that the schema lets be queried
         */
        foreach (
            [
                CustomPostsModule::class => '/ConditionalOnModule/CustomPosts',
                CategoriesModule::class => '/ConditionalOnModule/Categories',
                TagsModule::class => '/ConditionalOnModule/Tags',
            ] as $moduleClass => $conditionalFolder
        ) {
            try {
                if (class_exists($moduleClass) && App::getModule($moduleClass)->isEnabled()) {
                    $this->initServices(dirname(__DIR__), $conditionalFolder);
                }
            } catch (ComponentNotExistsException) {
            }
        }
    }
}
