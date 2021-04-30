<?php

declare(strict_types=1);

namespace PoP\API\Hooks;

use PoP\API\ComponentConfiguration;
use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoP\API\Response\Schemes as APISchemes;

class RoutingHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            '\PoP\Routing:uri-route',
            array($this, 'getURIRoute')
        );

        $this->hooksAPI->addFilter(
            '\PoP\ComponentModel\Engine:getExtraRoutes',
            array($this, 'getExtraRoutes'),
            10,
            1
        );
    }

    public function getExtraRoutes(array $extraRoutes): array
    {
        // The API cannot use getExtraRoutes()!!!!!
        // Because the fields can't be applied to different resources!
        // (Eg: author/leo/ and author/leo/?route=posts)
        $vars = ApplicationState::getVars();
        if (isset($vars['scheme']) && $vars['scheme'] == APISchemes::API) {
            return [];
        }

        return $extraRoutes;
    }

    /**
     * Replace REQUEST_URI with the website's home URL.
     * Watch out: If the homeURL is not contained in the current URL
     * then there's a misconfiguration in the server
     */
    public function getURIRoute(string $route): string
    {
        if (!ComponentConfiguration::overrideRequestURI()) {
            return $route;
        }
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $homeURL = $cmsengineapi->getHomeURL();
        $currentURL = RequestUtils::getCurrentUrl();
        // Remove the protocol to avoid erroring on http/https
        $homeURL = preg_replace('#^https?://#', '', $homeURL);
        $currentURL = preg_replace('#^https?://#', '', $currentURL);
        if (substr($currentURL, 0, strlen($homeURL)) != $homeURL) {
            // This is too harsh. Just ignore hook
            // throw new Exception(sprintf(
            //     'The webserver is not configured properly, since the current URL \'%s\' does not contain the home URL \'%s\' (possibly the server name has not been set-up correctly)',
            //     $currentURL,
            //     $homeURL
            // ));
            return $route;
        }
        return substr($currentURL, strlen($homeURL));
    }
}
