<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Hooks;

use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\EndpointHelpers;
use PoP\Root\Hooks\AbstractHookSet;

class PrintEndpointInEditorHookSet extends AbstractHookSet
{
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?EndpointHelpers $endpointHelpers = null;

    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = $this->instanceManager->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
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

        /**
         * Print the JS code after asset 'wp-blocks' as
         * it is always printed in the Block Editor (any such
         * script will do)
         */
        $jsScriptCode = 'var %s = "%s";';

        /**
         * Endpoint to allow developers to feed data to their Gutenberg blocks,
         * with pre-defined config (avoiding the user preferences from the plugin).
         */
        wp_add_inline_script('wp-blocks', \sprintf(
            $jsScriptCode,
            'GATOGRAPHQL_BLOCK_EDITOR_ADMIN_ENDPOINT',
            $this->getEndpointHelpers()->getAdminBlockEditorGraphQLEndpoint()
        ));
    }
}
