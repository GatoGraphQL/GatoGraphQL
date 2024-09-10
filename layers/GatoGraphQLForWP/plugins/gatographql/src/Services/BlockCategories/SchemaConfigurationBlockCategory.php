<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\BlockCategories;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;

class SchemaConfigurationBlockCategory extends AbstractBlockCategory
{
    public final const SCHEMA_CONFIGURATION_BLOCK_CATEGORY = 'gatographql-schema-config';

    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;

    final public function setGraphQLSchemaConfigurationCustomPostType(GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType): void
    {
        $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
    }
    final protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType
    {
        if ($this->graphQLSchemaConfigurationCustomPostType === null) {
            /** @var GraphQLSchemaConfigurationCustomPostType */
            $graphQLSchemaConfigurationCustomPostType = $this->instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);
            $this->graphQLSchemaConfigurationCustomPostType = $graphQLSchemaConfigurationCustomPostType;
        }
        return $this->graphQLSchemaConfigurationCustomPostType;
    }

    public function isServiceEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableSchemaConfiguration()) {
            return false;
        }
        return parent::isServiceEnabled();
    }

    /**
     * Custom Post Type for which to enable the block category
     *
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->getGraphQLSchemaConfigurationCustomPostType()->getCustomPostType(),
        ];
    }

    /**
     * Block category's slug
     */
    protected function getBlockCategorySlug(): string
    {
        return self::SCHEMA_CONFIGURATION_BLOCK_CATEGORY;
    }

    /**
     * Block category's title
     */
    protected function getBlockCategoryTitle(): string
    {
        return __('Schema Configuration for GraphQL', 'gatographql');
    }
}
