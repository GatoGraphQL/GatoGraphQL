<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointExecuters;

use GraphQLAPI\GraphQLAPI\ConditionalOnContext\Admin\Services\EndpointExecuters\AdminEndpointExecuterServiceTagInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\AbstractEndpointExecuter;
use GraphQLAPI\GraphQLAPI\Services\EndpointExecuters\GraphQLEndpointExecuterInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use GraphQLByPoP\GraphQLRequest\Execution\QueryRetrieverInterface;
use PoP\EngineWP\HelperServices\TemplateHelpersInterface;
use WP_Post;

class AdminEndpointExecuter extends AbstractEndpointExecuter implements AdminEndpointExecuterServiceTagInterface, GraphQLEndpointExecuterInterface
{
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?QueryRetrieverInterface $queryRetriever = null;
    private ?EndpointHelpers $endpointHelpers = null;
    private ?TemplateHelpersInterface $templateHelpers = null;

    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }
    final public function setQueryRetriever(QueryRetrieverInterface $queryRetriever): void
    {
        $this->queryRetriever = $queryRetriever;
    }
    final protected function getQueryRetriever(): QueryRetrieverInterface
    {
        return $this->queryRetriever ??= $this->instanceManager->getInstance(QueryRetrieverInterface::class);
    }
    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }
    final public function setTemplateHelpers(TemplateHelpersInterface $templateHelpers): void
    {
        $this->templateHelpers = $templateHelpers;
    }
    final protected function getTemplateHelpers(): TemplateHelpersInterface
    {
        return $this->templateHelpers ??= $this->instanceManager->getInstance(TemplateHelpersInterface::class);
    }

    /**
     * Provide the query to execute and its variables
     *
     * @return mixed[] Array of 2 elements: [query, variables]
     */
    public function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): array
    {
        /**
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        return $this->getQueryRetriever()->extractRequestedGraphQLQueryPayload();
    }

    public function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool
    {
        return false;
    }

    /**
     * Execute the GraphQL query when posting to:
     * /wp-admin/edit.php?page=graphql_api&action=execute_query
     */
    public function isEndpointBeingRequested(): bool
    {
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return false;
        }
        return $this->getEndpointHelpers()->isRequestingAdminConfigurableSchemaGraphQLEndpoint();
    }

    public function executeEndpoint(): void
    {
        \add_action(
            'admin_init',
            $this->includeJSONOutputTemplateAndExit(...)
        );
    }

    /**
     * To print the JSON output, we use WordPress templates,
     * which are used only in the front-end.
     * When in the admin, we must manually load the template,
     * and then exit
     */
    public function includeJSONOutputTemplateAndExit(): void
    {
        // Make sure the user has access to the editor
        if ($this->getUserAuthorization()->canAccessSchemaEditor()) {
            include $this->getTemplateHelpers()->getGenerateDataAndPrepareAndSendResponseTemplateFile();
            die;
        }
    }
}
