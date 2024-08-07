<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GuzzleHttp\RequestOptions;
use PoP\ComponentModel\Constants\FrameworkParams;

use function getenv;

/**
 * Tests that require to call the REST API to perform some action
 * before/after running the test.
 *
 * That's why these tests are done with the authenticated user
 * in WordPress, so the user can execute operations via the REST endpoint.
 */
trait RequestRESTAPIWebserverRequestTestTrait
{
    /**
     * @return array<string,mixed>
     */
    abstract protected static function getRequestBasicOptions(): array;

    /**
     * Basic options for the Request:
     *
     * - Must add the X-WP-Nonce header for the authenticated user.
     * - Add support for XDebug to the REST API call
     *
     * @see https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
     * @return array<string,mixed>
     */
    protected function getRESTEndpointRequestOptions(): array
    {
        $options = static::getRequestBasicOptions();
        $xdebugTrigger = getenv(FrameworkParams::XDEBUG_TRIGGER);
        if ($xdebugTrigger !== false) {
            $options[RequestOptions::QUERY][FrameworkParams::XDEBUG_TRIGGER] = $xdebugTrigger;
            /**
             * Must also pass ?XDEBUG_SESSION_STOP=1 in the URL to avoid
             * setting cookie XDEBUG_SESSION="1", which launches the
             * debugger every single time
             */
            $options[RequestOptions::QUERY][FrameworkParams::XDEBUG_SESSION_STOP] = '1';
        }
        return $options;
    }
}
