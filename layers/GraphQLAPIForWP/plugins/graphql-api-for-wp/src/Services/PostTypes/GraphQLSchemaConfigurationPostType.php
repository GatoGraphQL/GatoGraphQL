<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\PostTypes;

use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigAccessControlListBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigCacheControlListBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigFieldDeprecationListBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\SchemaConfigOptionsBlock;
use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\AccessControlFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\VersioningFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\PostTypes\AbstractPostType;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

class GraphQLSchemaConfigurationPostType extends AbstractPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-schemaconfig';

    /**
     * Custom Post Type name
     *
     * @return string
     */
    protected function getPostType(): string
    {
        return self::POST_TYPE;
    }

    /**
     * Module that enables this PostType
     */
    public function getEnablingModule(): ?string
    {
        return SchemaConfigurationFunctionalityModuleResolver::SCHEMA_CONFIGURATION;
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 3;
    }

    /**
     * Custom post type name
     */
    public function getPostTypeName(): string
    {
        return \__('Schema Configuration', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('Schema Configurations', 'graphql-api');
    }

    /**
     * Whenever this CPT is saved/updated, the timestamp must be regenerated,
     * because it contains Field Deprecation Lists,
     * which can change the schema
     *
     * @return boolean
     */
    protected function regenerateTimestampOnSave(): bool
    {
        return true;
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     *
     * @return boolean
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array<array> Every element is an array with template name in first pos, and attributes then
     */
    protected function getGutenbergTemplate(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        $template = [];
        // Add blocks depending on being enabled by module
        $blockClassModules = [
            SchemaConfigAccessControlListBlock::class,
            SchemaConfigCacheControlListBlock::class,
            SchemaConfigFieldDeprecationListBlock::class,
        ];
        foreach ($blockClassModules as $blockClass) {
            /**
             * @var AbstractBlock
             */
            $block = $instanceManager->getInstance($blockClass);
            if ($block->isServiceEnabled()) {
                $template[] = [$block->getBlockFullName()];
            }
        }
        // Add the Configuration block always
        /**
         * @var SchemaConfigOptionsBlock
         */
        $schemaConfigOptionsBlock = $instanceManager->getInstance(SchemaConfigOptionsBlock::class);
        $template[] = [$schemaConfigOptionsBlock->getBlockFullName()];
        return $template;
    }

    /**
     * Indicates if to lock the Gutenberg templates
     *
     * @return boolean
     */
    protected function lockGutenbergTemplate(): bool
    {
        return true;
    }
}
