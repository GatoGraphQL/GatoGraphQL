<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Taxonomies;

use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPublicPersistedQueryEndpointCustomPostType;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class GraphQLEndpointCategoryTaxonomy extends AbstractCategory
{
    use StandaloneServiceTrait;

    private ?GraphQLPublicPersistedQueryEndpointCustomPostType $graphQLPublicPersistedQueryEndpointCustomPostType = null;
    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;

    final public function setGraphQLPublicPersistedQueryEndpointCustomPostType(GraphQLPublicPersistedQueryEndpointCustomPostType $graphQLPublicPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPublicPersistedQueryEndpointCustomPostType = $graphQLPublicPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPublicPersistedQueryEndpointCustomPostType(): GraphQLPublicPersistedQueryEndpointCustomPostType
    {
        /** @var GraphQLPublicPersistedQueryEndpointCustomPostType */
        return $this->graphQLPublicPersistedQueryEndpointCustomPostType ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLPublicPersistedQueryEndpointCustomPostType::class);
    }
    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        return $this->graphQLCustomEndpointCustomPostType ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }

    public function getTaxonomy(): string
    {
        return 'graphql-endpoint-category';
    }

    public function getTaxonomyName(bool $titleCase = true): string
    {
        return $titleCase ? \__('GraphQL Endpoint Category', 'graphql-api') : \__('GraphQL endpoint category', 'graphql-api');
    }

    /**
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    public function getTaxonomyPluralNames(bool $titleCase = true): string
    {
        return $titleCase ? \__('Endpoint Categories', 'graphql-api') : \__('endpoint categories', 'graphql-api');
    }

    /**
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->getGraphQLPublicPersistedQueryEndpointCustomPostType()->getCustomPostType(),
            $this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType(),
        ];
    }
}
