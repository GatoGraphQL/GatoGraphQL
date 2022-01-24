<?php

class PoP_Domain_Utils
{
    public static function init(): void
    {
        \PoP\Root\App::addFilter(
            'PoP_Application_Utils:request-domain',
            array(self::class, 'maybeGetRequestDomain')
        );
    }

    public static function maybeGetRequestDomain($domain)
    {
        if ($request_domain = self::getDomainFromRequest()) {
            return $request_domain;
        }

        return $domain;
    }

    public static function getDomainFromRequest()
    {
        $domain = $_GET[POP_URLPARAM_DOMAIN] ?? null;
        return $domain ? urldecode($domain) : '';
    }
}

/**
 * Initialization
 */
PoP_Domain_Utils::init();
