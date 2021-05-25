<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

interface RequestHelperServiceInterface
{
    public function getCurrentURL(): string;
    /**
     * Return the requested full URL
     *
     * @param boolean $useHostRequestedByClient If true, get the host from user-provided HTTP_HOST, otherwise from the server-defined SERVER_NAME
     */
    public function getRequestedFullURL(bool $useHostRequestedByClient = false): string;
}
