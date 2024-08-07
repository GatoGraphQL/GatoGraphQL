<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use GuzzleHttp\RequestOptions;

/**
 * Tests that require to call the REST API to perform some action
 * before/after running the test.
 *
 * That's why these tests are done with the authenticated user
 * in WordPress, so the user can execute operations via the REST endpoint.
 */
trait RequestRESTAPIWordPressAuthenticatedUserWebserverRequestTestTrait
{
    use RequestRESTAPIWebserverRequestTestTrait {
        RequestRESTAPIWebserverRequestTestTrait::getRESTEndpointRequestOptions as upstreamGetRESTEndpointRequestOptions;
    }
    use WordPressAuthenticatedUserWebserverRequestTestCaseTrait;

    protected static function useSSL(): bool
    {
        return true;
    }

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
        $options = $this->upstreamGetRESTEndpointRequestOptions();
        $options[RequestOptions::HEADERS]['X-WP-Nonce'] = static::$wpRESTNonce;
        return $options;
    }
}
