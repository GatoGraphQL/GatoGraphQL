<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolver;
use WP_Post;

abstract class AbstractEndpointFunctionalityModuleResolver extends AbstractFunctionalityModuleResolver
{
    use CommonModuleResolverTrait;
    use EndpointFunctionalityModuleResolverTrait;

    /** @var WP_Post[]|null */
    protected ?array $schemaConfigurationCustomPosts = null;

    private ?MarkdownContentParserInterface $markdownContentParser = null;
    private ?GraphQLSchemaConfigurationCustomPostType $graphQLSchemaConfigurationCustomPostType = null;

    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        if ($this->markdownContentParser === null) {
            /** @var MarkdownContentParserInterface */
            $markdownContentParser = $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
            $this->markdownContentParser = $markdownContentParser;
        }
        return $this->markdownContentParser;
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

    public function getSettingsCategory(string $module): string
    {
        return SettingsCategoryResolver::ENDPOINT_CONFIGURATION;
    }

    /**
     * @return WP_Post[]
     */
    protected function getSchemaConfigurationCustomPosts(): array
    {
        if ($this->schemaConfigurationCustomPosts === null) {
            $this->schemaConfigurationCustomPosts = $this->doGetSchemaConfigurationCustomPosts();
        }

        return $this->schemaConfigurationCustomPosts;
    }

    /**
     * @return WP_Post[]
     */
    protected function doGetSchemaConfigurationCustomPosts(): array
    {
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $graphQLSchemaConfigurationCustomPostType = $this->getGraphQLSchemaConfigurationCustomPostType();

        return $this->getSchemaEntityListCustomPosts($graphQLSchemaConfigurationCustomPostType->getCustomPostType());
    }
}
