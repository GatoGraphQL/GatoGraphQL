<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

interface RequestHelperServiceInterface
{
    public function getCurrentURL(): ?string;

    /**
     * Return the requested full URL
     *
     * @param boolean $useHostRequestedByClient If true, get the host from user-provided HTTP_HOST, otherwise from the server-defined SERVER_NAME
     */
    public function getRequestedFullURL(bool $useHostRequestedByClient = false): ?string;

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
