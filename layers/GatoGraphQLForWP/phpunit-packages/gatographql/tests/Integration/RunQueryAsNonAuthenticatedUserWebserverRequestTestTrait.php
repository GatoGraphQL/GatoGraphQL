<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\RequestOptions;

/**
 * Run the GraphQL query as a non-authenticated user, even when the plugin
 * settings are modified as an authenticated administrator beforehand.
 *
 * Administrators bypass the option allow/denylist, so to assert that the
 * list is actually applied (and to get a deterministic response), the query
 * itself must be executed as a non-administrator. The settings are still
 * modified as admin via the REST API, which uses its own request options.
 */
trait RunQueryAsNonAuthenticatedUserWebserverRequestTestTrait
{
    /**
     * @param array<string,mixed> $options
     * @return array<string,mixed>
     */
    protected function customizeRequestOptions(array $options): array
    {
        $options = parent::customizeRequestOptions($options);
        $options[RequestOptions::COOKIES] = new CookieJar();
        return $options;
    }
}
