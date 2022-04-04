<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\Hooks;

use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoP\Root\Routing\HookNames;
use PoPCMSSchema\SchemaCommons\CMS\CMSServiceInterface;
use PoPCMSSchema\SchemaCommons\Component;
use PoPCMSSchema\SchemaCommons\ComponentConfiguration;

class RoutingHookSet extends AbstractHookSet
{
    private ?CMSServiceInterface $cmsService = null;
    private ?RequestHelperServiceInterface $requestHelperService = null;

    final public function setCMSService(CMSServiceInterface $cmsService): void
    {
        $this->cmsService = $cmsService;
    }
    final protected function getCMSService(): CMSServiceInterface
    {
        return $this->cmsService ??= $this->instanceManager->getInstance(CMSServiceInterface::class);
    }
    final public function setRequestHelperService(RequestHelperServiceInterface $requestHelperService): void
    {
        $this->requestHelperService = $requestHelperService;
    }
    final protected function getRequestHelperService(): RequestHelperServiceInterface
    {
        return $this->requestHelperService ??= $this->instanceManager->getInstance(RequestHelperServiceInterface::class);
    }

    protected function init(): void
    {
        App::addFilter(
            HookNames::REQUEST_URI,
            $this->maybeOverrideURIRoute(...)
        );
    }

    /**
     * Replace REQUEST_URI with the website's home URL.
     *
     * Watch out: If the homeURL is not contained in the current URL,
     * then there's a misconfiguration in the server
     */
    public function maybeOverrideURIRoute(string $route): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->overrideRequestURI()) {
            return $route;
        }

        $currentURL = $this->getRequestHelperService()->getCurrentURL();
        if ($currentURL === null) {
            return $route;
        }

        $homeURL = $this->getCMSService()->getHomeURL();

        // Remove the protocol to avoid erroring on http/https
        $homeURL = preg_replace('#^https?://#', '', $homeURL);
        $currentURL = preg_replace('#^https?://#', '', $currentURL);
        if (substr($currentURL, 0, strlen($homeURL)) !== $homeURL) {
            // This is too harsh. Just ignore hook
            // throw new GenericSystemException(sprintf(
            //     'The webserver is not configured properly, since the current URL \'%s\' does not contain the home URL \'%s\' (possibly the server name has not been set-up correctly)',
            //     $currentURL,
            //     $homeURL
            // ));
            return $route;
        }
        return substr($currentURL, strlen($homeURL));
    }
}
