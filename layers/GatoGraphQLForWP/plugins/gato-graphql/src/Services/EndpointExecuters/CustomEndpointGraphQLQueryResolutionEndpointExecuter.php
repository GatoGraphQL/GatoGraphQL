<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use WP_Post;

class CustomEndpointGraphQLQueryResolutionEndpointExecuter extends AbstractGraphQLQueryResolutionEndpointExecuter
{
    private ?GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType = null;

    final public function setGraphQLCustomEndpointCustomPostType(GraphQLCustomEndpointCustomPostType $graphQLCustomEndpointCustomPostType): void
    {
        $this->graphQLCustomEndpointCustomPostType = $graphQLCustomEndpointCustomPostType;
    }
    final protected function getGraphQLCustomEndpointCustomPostType(): GraphQLCustomEndpointCustomPostType
    {
        /** @var GraphQLCustomEndpointCustomPostType */
        return $this->graphQLCustomEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);
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
     * @return array{0:?string,1:?array<string,mixed>} Array of 2 elements: [query, variables]
     */
    public function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array
    {
        /**
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        $graphQLQueryPayload = $this->getQueryRetriever()->extractRequestedGraphQLQueryPayload();
        return [
            $graphQLQueryPayload->query,
            $graphQLQueryPayload->variables,
        ];
    }
}
