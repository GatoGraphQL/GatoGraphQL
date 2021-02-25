<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\PostTypes;

use GraphQLAPI\GraphQLAPI\Blocks\AbstractBlock;
use GraphQLAPI\GraphQLAPI\PostTypes\AbstractPostType;
use GraphQLAPI\GraphQLAPI\Facades\Registries\ModuleRegistryFacade;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigOptionsBlock;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigCacheControlListBlock;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigAccessControlListBlock;
use GraphQLAPI\GraphQLAPI\Blocks\SchemaConfigFieldDeprecationListBlock;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\VersioningFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\PerformanceFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\HybridServices\ModuleResolvers\AccessControlFunctionalityModuleResolver;

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
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $template = [];
        // Add blocks depending on being enabled by module
        $blockClassModules = [
            SchemaConfigAccessControlListBlock::class => AccessControlFunctionalityModuleResolver::ACCESS_CONTROL,
            SchemaConfigCacheControlListBlock::class => PerformanceFunctionalityModuleResolver::CACHE_CONTROL,
            SchemaConfigFieldDeprecationListBlock::class => VersioningFunctionalityModuleResolver::FIELD_DEPRECATION,
        ];
        foreach ($blockClassModules as $blockClass => $module) {
            if ($moduleRegistry->isModuleEnabled($module)) {
                /**
                 * @var AbstractBlock
                 */
                $block = $instanceManager->getInstance($blockClass);
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
