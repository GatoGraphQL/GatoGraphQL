<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\Services\EndpointExecuters;

use GatoGraphQL\GatoGraphQL\ConditionalOnContext\Admin\Services\EndpointExecuters\AdminEndpointExecuterServiceTagInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ObjectModels\NullableGraphQLQueryVariablesEntry;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\AbstractEndpointExecuter;
use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\GraphQLEndpointExecuterInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
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
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = $this->instanceManager->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
    }
    final public function setQueryRetriever(QueryRetrieverInterface $queryRetriever): void
    {
        $this->queryRetriever = $queryRetriever;
    }
    final protected function getQueryRetriever(): QueryRetrieverInterface
    {
        if ($this->queryRetriever === null) {
            /** @var QueryRetrieverInterface */
            $queryRetriever = $this->instanceManager->getInstance(QueryRetrieverInterface::class);
            $this->queryRetriever = $queryRetriever;
        }
        return $this->queryRetriever;
    }
    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        if ($this->endpointHelpers === null) {
            /** @var EndpointHelpers */
            $endpointHelpers = $this->instanceManager->getInstance(EndpointHelpers::class);
            $this->endpointHelpers = $endpointHelpers;
        }
        return $this->endpointHelpers;
    }
    final public function setTemplateHelpers(TemplateHelpersInterface $templateHelpers): void
    {
        $this->templateHelpers = $templateHelpers;
    }
    final protected function getTemplateHelpers(): TemplateHelpersInterface
    {
        if ($this->templateHelpers === null) {
            /** @var TemplateHelpersInterface */
            $templateHelpers = $this->instanceManager->getInstance(TemplateHelpersInterface::class);
            $this->templateHelpers = $templateHelpers;
        }
        return $this->templateHelpers;
    }

    /**
     * Provide the query to execute and its variables
     */
    public function getGraphQLQueryAndVariables(?WP_Post $graphQLQueryPost): NullableGraphQLQueryVariablesEntry
    {
        /**
         * Extract the query from the BODY through standard GraphQL endpoint execution
         */
        $graphQLQueryPayload = $this->getQueryRetriever()->extractRequestedGraphQLQueryPayload();
        return new NullableGraphQLQueryVariablesEntry(
            $graphQLQueryPayload->query,
            $graphQLQueryPayload->variables,
        );
    }

    public function doURLParamsOverrideGraphQLVariables(?WP_Post $customPost): bool
    {
        return false;
    }

    /**
     * Execute the GraphQL query when posting to:
     * /wp-admin/edit.php?page=gatographql&action=execute_query
     */
    public function isEndpointBeingRequested(): bool
    {
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return false;
        }

        /**
         * If the Private Endpoint module is disabled,
         * remove access to the "default" admin endpoint
         */
        $endpointHelpers = $this->getEndpointHelpers();
        if ($endpointHelpers->isRequestingDefaultAdminGraphQLEndpoint()) {
            return $this->getModuleRegistry()->isModuleEnabled(EndpointFunctionalityModuleResolver::PRIVATE_ENDPOINT);
        }

        return $endpointHelpers->isRequestingAdminGraphQLEndpoint();
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
