<?php

declare(strict_types=1);

namespace PoP\API\Hooks;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\API\ComponentConfiguration;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\AbstractHookSet;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;

class RoutingHookSet extends AbstractHookSet
{
    protected CMSServiceInterface $cmsService;
    protected RequestHelperServiceInterface $requestHelperService;

    #[Required]
    public function autowireRoutingHookSet(
        CMSServiceInterface $cmsService,
        RequestHelperServiceInterface $requestHelperService,
    ) {
        $this->cmsService = $cmsService;
        $this->requestHelperService = $requestHelperService;
    }

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
        $homeURL = $this->cmsService->getHomeURL();
        $currentURL = $this->requestHelperService->getCurrentURL();
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
