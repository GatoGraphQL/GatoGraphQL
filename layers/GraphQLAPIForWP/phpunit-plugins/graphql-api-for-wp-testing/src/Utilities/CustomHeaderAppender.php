<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPITesting\Utilities;

use PHPUnitForGraphQLAPI\GraphQLAPITesting\Constants\CustomHeaders;

use function add_filter;
use function is_user_logged_in;
use function wp_create_nonce;

class CustomHeaderAppender
{
    public function __construct()
    {
        add_filter(
            'init',
            $this->addRESTNonceAsHeader(...)
        );
    }

    /**
     * Send the WP REST nonce as a header, to make it easier
     * to execute REST endpoints for integration tests.
     *
     * @see layers/GraphQLAPIForWP/phpunit-packages/webserver-requests/tests/AbstractThirdPartyPluginDependencyWordPressAuthenticatedUserWebserverRequestTest.php
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
