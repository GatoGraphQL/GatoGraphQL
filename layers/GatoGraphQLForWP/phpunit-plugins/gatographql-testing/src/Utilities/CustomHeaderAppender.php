<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Utilities;

use PHPUnitForGatoGraphQL\GatoGraphQLTesting\Constants\CustomHeaders;

use function add_action;
use function is_user_logged_in;
use function wp_create_nonce;

class CustomHeaderAppender
{
    public function __construct()
    {
        add_action(
            'init',
            $this->addRESTNonceAsHeader(...)
        );
    }

    /**
     * Send the WP REST nonce as a header, to make it easier
     * to execute REST endpoints for integration tests.
     *
     * @see layers/GatoGraphQLForWP/phpunit-packages/webserver-requests/tests/AbstractThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTestCase.php
     */
    public function addRESTNonceAsHeader(): void
    {
        if (!is_user_logged_in()) {
            return;
        }

        header(sprintf(
            '%s: %s',
            CustomHeaders::WP_REST_NONCE,
            wp_create_nonce('wp_rest')
        ));
    }
}
