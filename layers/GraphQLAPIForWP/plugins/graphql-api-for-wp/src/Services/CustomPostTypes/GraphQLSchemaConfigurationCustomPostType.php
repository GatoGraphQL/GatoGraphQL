<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\CustomPostTypes;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\BlockRegistryInterface;
use GraphQLAPI\GraphQLAPI\Registries\SchemaConfigBlockRegistryInterface;

class GraphQLSchemaConfigurationCustomPostType extends AbstractCustomPostType
{
    use WithBlockRegistryCustomPostTypeTrait;

    private ?SchemaConfigBlockRegistryInterface $schemaConfigBlockRegistry = null;

    final public function setSchemaConfigBlockRegistry(SchemaConfigBlockRegistryInterface $schemaConfigBlockRegistry): void
    {
        $this->schemaConfigBlockRegistry = $schemaConfigBlockRegistry;
    }
    final protected function getSchemaConfigBlockRegistry(): SchemaConfigBlockRegistryInterface
    {
        return $this->schemaConfigBlockRegistry ??= $this->instanceManager->getInstance(SchemaConfigBlockRegistryInterface::class);
    }

    /**
     * Custom Post Type name
     */
    public function getCustomPostType(): string
    {
        return 'graphql-schemaconfig';
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
    public function getCustomPostTypeName(): string
    {
        return \__('Schema Configuration', 'graphql-api');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     */
    protected function getCustomPostTypePluralNames(bool $uppercase): string
    {
        return \__('Schema Configurations', 'graphql-api');
    }

    /**
     * Whenever this CPT is saved/updated, the timestamp must be regenerated,
     * because it contains Field Deprecation Lists,
     * which can change the schema
     */
    protected function regenerateTimestampOnSave(): bool
    {
        return true;
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    protected function getBlockRegistry(): BlockRegistryInterface
    {
        return $this->getSchemaConfigBlockRegistry();
    }
}
