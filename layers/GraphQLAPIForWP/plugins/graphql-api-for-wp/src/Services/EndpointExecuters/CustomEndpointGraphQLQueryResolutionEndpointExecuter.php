<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface;
use Symfony\Contracts\Service\Attribute\Required;
use WP_Post;

class CustomEndpointGraphQLQueryResolutionEndpointExecuter extends AbstractGraphQLQueryResolutionEndpointExecuter implements CustomEndpointExecuterServiceTagInterface
{
    protected ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;
    protected ?QueryRetrieverInterface $queryRetrieverInterface = null;

    public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        return $this->graphQLCustomEndpointCustomPostType ??= $this->getInstanceManager()->getInstance(GraphQLCustomEndpointCustomPostType::class);
    }
    public function setQueryRetriever(QueryRetrieverInterface $queryRetrieverInterface): void
    {
        $this->queryRetrieverInterface = $queryRetrieverInterface;
    }
    protected function getQueryRetriever(): QueryRetrieverInterface
    {
        return $this->queryRetrieverInterface ??= $this->getInstanceManager()->getInstance(QueryRetrieverInterface::class);
    }

    //#[Required]
    final public function autowireCustomEndpointGraphQLQueryResolutionEndpointExecuter(
        GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType,
        QueryRetrieverInterface $queryRetrieverInterface,
    ): void {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
        $this->queryRetrieverInterface = $queryRetrieverInterface;
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::CUSTOM_ENDPOINTS;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLCustomEndpointCustomPostType();
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return mixed[] Array of 2 elements: [query, variables]
     */
    protected function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array
    {
        /**
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        return $this->getQueryRetrieverInterface()->extractRequestedGraphQLQueryPayload();
    }
}
