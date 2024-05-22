<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

interface RequestHelperServiceInterface
{
    /**
     * Return the requested full URL
     */
    public function getRequestedFullURL(): ?string;

    /**
     * Return the URL that is useful to the component model:
     * The full URL minus those params that can be made invisible
     * to the end user.
     */
    public function getComponentModelCurrentURL(): ?string;

    /**
     * Retrieve the visitor's IP address. If the property name
     * to query under $_SERVER is not the right one (see below),
     * it shall return `null`.
     *
     * By default it gets the IP from $_SERVER['REMOTE_ADDR'],
     * and the property name can be configured via the environmen
     * variable `CLIENT_IP_ADDRESS_SERVER_PROPERTY_NAME`.
     *
     * Depending on the environment, some candidates are:
     *
     * - 'HTTP_CLIENT_IP'
     * - 'HTTP_CF_CONNECTING_IP' (for Cloudflare)
     * - 'HTTP_X_FORWARDED_FOR' (for AWS)
     */
    public function getClientIPAddress(): ?string;
}
