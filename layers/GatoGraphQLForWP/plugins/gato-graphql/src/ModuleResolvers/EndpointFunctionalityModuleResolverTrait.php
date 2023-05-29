<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\ModuleTypeResolvers\ModuleTypeResolver;
use WP_Post;

trait EndpointFunctionalityModuleResolverTrait
{
    /** @var WP_Post[]|null */
    protected ?array $schemaConfigurationCustomPosts = null;

    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return 190;
    }

    /**
     * Enable to customize a specific UI for the module
     */
    public function getModuleType(string $module): string
    {
        return ModuleTypeResolver::ENDPOINT;
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

        return \get_posts([
            'posts_per_page' => -1,
            'post_type' => $graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
            'post_status' => 'publish',
        ]);
    }

    abstract protected function getGraphQLSchemaConfigurationCustomPostType(): GraphQLSchemaConfigurationCustomPostType;
}
