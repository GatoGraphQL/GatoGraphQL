<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQLTesting\Webserver;

use function add_filter;

/**
 * When not using Lando with a proxy, the assigned URL
 * will be something like "localhost:54023", however,
 * this URL is not accessible from within the container,
 * as the port is not mapped.
 *
 * Hence, convert this into "localhost".
 */
class LandoAdapter
{
    public function __construct()
    {
        add_filter('option_siteurl', $this->maybeRemovePortFromLocalhostURL(...));
        add_filter('option_home', $this->maybeRemovePortFromLocalhostURL(...));
    }

    public function maybeRemovePortFromLocalhostURL(string $url): string
    {
        if (str_starts_with($url, 'https://localhost:')) {
            return 'https://localhost';
        }
        if (str_starts_with($url, 'http://localhost:')) {
            return 'http://localhost';
        }
        return $url;
    }
}
