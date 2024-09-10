<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Taxonomies;

use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class GraphQLEndpointCategoryTaxonomy extends AbstractCategory
{
    use StandaloneServiceTrait;

    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;
    private ?GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType = null;

    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        if ($this->graphQLCustomEndpointCustomPostType === null) {
            /** @var GraphQLCustomEndpointCustomPostType */
            $graphQLCustomEndpointCustomPostType = InstanceManagerFacade::getInstance()->getInstance(GraphQLCustomEndpointCustomPostType::class);
            $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
        }
        return $this->graphQLCustomEndpointCustomPostType;
    }
    final public function setGraphQLPersistedQueryEndpointCustomPostType(GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        if ($this->graphQLPersistedQueryEndpointCustomPostType === null) {
            /** @var GraphQLPersistedQueryEndpointCustomPostType */
            $graphQLPersistedQueryEndpointCustomPostType = InstanceManagerFacade::getInstance()->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
            $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
        }
        return $this->graphQLPersistedQueryEndpointCustomPostType;
    }

    public function isServiceEnabled(): bool
    {
        if (!parent::isServiceEnabled()) {
            return false;
        }
        return $this->getCustomPostTypes() !== [];
    }

    public function getTaxonomy(): string
    {
        return 'graphql-endpoint-category';
    }

    public function getTaxonomyName(bool $titleCase = true): string
    {
        return $titleCase ? \__('GraphQL Endpoint Category', 'gatographql') : \__('GraphQL endpoint category', 'gatographql');
    }

    /**
     * @param bool $titleCase Indicate if the name must be title case (for starting a sentence) or, otherwise, lowercase
     */
    public function getTaxonomyPluralNames(bool $titleCase = true): string
    {
        return $titleCase ? \__('Endpoint Categories', 'gatographql') : \__('endpoint categories', 'gatographql');
    }

    /**
     * @return string[]
     */
    public function getCustomPostTypes(): array
    {
        return [
            $this->getGraphQLCustomEndpointCustomPostType()->getCustomPostType(),
            $this->getGraphQLPersistedQueryEndpointCustomPostType()->getCustomPostType(),
        ];
    }
}
