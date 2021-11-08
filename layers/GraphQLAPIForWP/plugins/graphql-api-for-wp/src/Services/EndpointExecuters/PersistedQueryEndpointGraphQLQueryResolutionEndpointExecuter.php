<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\Blocks\PersistedQueryEndpointOptionsBlock;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLEndpointCustomPostTypeInterface;
use GraphQLAPI\GraphQLAPI\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GraphQLQueryPostTypeHelpers;
use GraphQLByPoP\GraphQLRequest\Hooks\VarsHookSet as GraphQLRequestVarsHooks;
use WP_Post;

class PersistedQueryEndpointGraphQLQueryResolutionEndpointExecuter extends AbstractGraphQLQueryResolutionEndpointExecuter implements PersistedQueryEndpointExecuterServiceTagInterface
{
    private ?GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType = null;
    private ?GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers = null;
    private ?GraphQLRequestVarsHooks $graphQLRequestVarsHooks = null;

    final public function setGraphQLPersistedQueryEndpointCustomPostType(GraphQLPersistedQueryEndpointCustomPostType $graphQLPersistedQueryEndpointCustomPostType): void
    {
        $this->graphQLPersistedQueryEndpointCustomPostType = $graphQLPersistedQueryEndpointCustomPostType;
    }
    final protected function getGraphQLPersistedQueryEndpointCustomPostType(): GraphQLPersistedQueryEndpointCustomPostType
    {
        return $this->graphQLPersistedQueryEndpointCustomPostType ??= $this->instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);
    }
    final public function setGraphQLQueryPostTypeHelpers(GraphQLQueryPostTypeHelpers $graphQLQueryPostTypeHelpers): void
    {
        $this->graphQLQueryPostTypeHelpers = $graphQLQueryPostTypeHelpers;
    }
    final protected function getGraphQLQueryPostTypeHelpers(): GraphQLQueryPostTypeHelpers
    {
        return $this->graphQLQueryPostTypeHelpers ??= $this->instanceManager->getInstance(GraphQLQueryPostTypeHelpers::class);
    }
    final public function setGraphQLRequestVarsHooks(GraphQLRequestVarsHooks $graphQLRequestVarsHooks): void
    {
        $this->graphQLRequestVarsHooks = $graphQLRequestVarsHooks;
    }
    final protected function getGraphQLRequestVarsHooks(): GraphQLRequestVarsHooks
    {
        return $this->graphQLRequestVarsHooks ??= $this->instanceManager->getInstance(GraphQLRequestVarsHooks::class);
    }

    public function getEnablingModule(): ?string
    {
        return EndpointFunctionalityModuleResolver::PERSISTED_QUERIES;
    }

    protected function getCustomPostType(): GraphQLEndpointCustomPostTypeInterface
    {
        return $this->getGraphQLPersistedQueryEndpointCustomPostType();
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
        return $this->getGraphQLQueryPostTypeHelpers()->getGraphQLQueryPostAttributes($graphQLQueryPost, true);
    }

    /**
     * Check if requesting the single post of this CPT and, in this case, set the request with the needed API params
     *
     * @param array<array> $vars_in_array
     */
    public function addGraphQLVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;

        // The Persisted Query is also standard GraphQL
        $this->getGraphQLRequestVarsHooks()->setStandardGraphQLVars($vars);

        // Remove the VarsHookSet from the GraphQLRequest, so it doesn't process the GraphQL query
        // Otherwise it will add error "The query in the body is empty"
        /**
         * @var callable
         */
        $action = [$this->getGraphQLRequestVarsHooks(), 'addVars'];
        \remove_action(
            'ApplicationState:addVars',
            $action,
            20
        );

        // Execute the original logic
        parent::addGraphQLVars($vars_in_array);
    }

    /**
     * Indicate if the GraphQL variables must override the URL params
     */
    protected function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool
    {
        if ($customPost === null) {
            return parent::doURLParamsOverrideGraphQLVariables($customPost);
        }
        $default = true;
        $optionsBlockDataItem = $this->getCustomPostType()->getOptionsBlockDataItem($customPost);
        if ($optionsBlockDataItem === null) {
            return $default;
        }

        // `true` is the default option in Gutenberg, so it's not saved to the DB!
        return $optionsBlockDataItem['attrs'][PersistedQueryEndpointOptionsBlock::ATTRIBUTE_NAME_ACCEPT_VARIABLES_AS_URL_PARAMS] ?? $default;
    }
}
