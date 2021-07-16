<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\AbstractGraphQLEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers;
use GraphQLByPoP\GraphQLRequest\Hooks\VarsHookSet as GraphQLRequestVarsHooks;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use WP_Post;

class PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter extends AbstractGraphQLQueryResolutionEndpointExecuter implements PersistedQueryEndpointExecuterServiceTagInterface
{
    public function __construct(
        InstanceManagerInterface $instanceManager,
        ModuleRegistryInterface $moduleRegistry,
        protected GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType,
        protected GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers,
    ) {
        parent::__construct(
            $instanceManager,
            $moduleRegistry,
        );
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }

    protected function getCustomPostType(): AbstractGraphQLEndpointCustomPostType
    {
        return $this->graphQLPersistedQueryEndpointCustomPostType;
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return mixed[] Array with 2 elements: [$graphQLQuery, $graphQLVariables]
     */
    protected function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array
    {
        /**
         * Extract the query from the post (or from its parents), and set it in $vars
         */
        return $this->graphQLQueryPostTypeHelpers->getGraphQLQueryPostAttributes($graphQLQueryPost, true);
    }

    /**
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @param array<array> $vars_in_array
     */
    public function addGraphQLVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;

        /** @var GraphQLRequestVarsHooks */
        $graphQLAPIRequestHookSet = $this->instanceManager->getInstance(GraphQLRequestVarsHooks::class);

        // The Persisted Query is also standard GraphQL
        $graphQLAPIRequestHookSet->setStandardGraphQLVars($vars);

        // Remove the VarsHookSet from the GraphQLRequest, so it doesn't process the GraphQL query
        // Otherwise it will add error "The query in the body is empty"
        /**
         * @var callable
         */
        $action = [$graphQLAPIRequestHookSet, 'addVars'];
        \remove_action(
            'ApplicationState:addVars',
            $action,
            20
        );

        // Execute the original logic
        parent::addGraphQLVars($vars_in_array);
    }
}
