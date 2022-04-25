<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use function getenv;
use PoP\ComponentModel\Constants\FrameworkParams;

/**
 * Tests that require to call the REST API to perform some action
 * before/after running the test.
 *
 * That's why these tests are done with the authenticated user
 * in WordPress, so the user can execute operations via the REST endpoint.
 */
trait RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait
{
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected static function useSSL(): bool
    {
        return true;
    }

    abstract protected static function getRequestBasicOptions(): array;

    /**
     * Basic options for the Request:
     *
     * - Must add the X-WP-Nonce header for the authenticated user.
     * - Add support for XDebug to the REST API call
     *
     * @see https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
     */
    protected function getRESTEndpointRequestOptions(): array
    {
        $options = static::getRequestBasicOptions();
        $options['headers']['X-WP-Nonce'] = static::$wpRESTNonce;
        $xdebugTrigger = getenv(FrameworkParams::XDEBUG_TRIGGER);
        if ($xdebugTrigger !== false) {
            $options['query'][FrameworkParams::XDEBUG_TRIGGER] = $xdebugTrigger;
        }
        return $options;
    }
}
