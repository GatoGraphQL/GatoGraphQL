<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Hooks;

use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EndpointHelpers;
use PoP\Root\Hooks\AbstractHookSet;

class PrintEndpointInEditorHookSet extends AbstractHookSet
{
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?EndpointHelpers $endpointHelpers = null;

    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        /** @var UserAuthorizationInterface */
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }
    final public function setEndpointHelpers(EndpointHelpers $endpointHelpers): void
    {
        $this->endpointHelpers = $endpointHelpers;
    }
    final protected function getEndpointHelpers(): EndpointHelpers
    {
        /** @var EndpointHelpers */
        return $this->endpointHelpers ??= $this->instanceManager->getInstance(EndpointHelpers::class);
    }

    protected function init(): void
    {
        /**
         * Print the global JS variables, required by the blocks
         */
        \add_action(
            'admin_print_scripts',
            $this->printAdminGraphQLEndpointVariables(...)
        );
    }

    /**
     * Print JS variables which are used by several blocks,
     * before the blocks are loaded
     */
    public function printAdminGraphQLEndpointVariables(): void
    {
        // Make sure the user has access to the editor
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return;
        }

        $scriptTag = '<script type="text/javascript">var %s = "%s"</script>';

        /**
         * Endpoint to allow developers to feed data to their Gutenberg blocks,
         * with pre-defined config (avoiding the user preferences from the plugin).
         */
        \printf(
            $scriptTag,
            'GRAPHQL_API_BLOCK_EDITOR_ADMIN_ENDPOINT',
            $this->getEndpointHelpers()->getAdminBlockEditorGraphQLEndpoint()
        );
    }
}
