<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\Definitions\Constants\Params as DefinitionsParams;
use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;

class RequestHelperService implements RequestHelperServiceInterface
{
    use BasicServiceTrait;

    public final const HOOK_CURRENT_URL_REMOVE_PARAMS = __CLASS__ . ':current-url:remove-params';

    /**
     * Return the requested full URL
     *
     * @param bool $useHostRequestedByClient If true, get the host from user-provided HTTP_HOST, otherwise from the server-defined SERVER_NAME
     */
    public function getRequestedFullURL(): ?string
    {
        if (!App::isHTTPRequest()) {
            return null;
        }

        return App::getRequest()->getRequestUri();
    }

    public function getComponentModelCurrentURL(): ?string
    {
        if (!App::isHTTPRequest()) {
            return null;
        }

        $requestedFullURL = $this->getRequestedFullURL();
        if ($requestedFullURL === null) {
            return null;
        }

        // Strip the Target and Output off it, users don't need to see those
        $remove_params = (array) App::applyFilters(
            self::HOOK_CURRENT_URL_REMOVE_PARAMS,
            [
                Params::VERSION,
                Params::COMPONENTFILTER,
                Params::COMPONENTPATHS,
                Params::ACTION_PATH,
                Params::DATA_OUTPUT_ITEMS,
                Params::DATA_SOURCE,
                Params::DATAOUTPUTMODE,
                Params::DATABASESOUTPUTMODE,
                Params::OUTPUT,
                Params::DATASTRUCTURE,
                DefinitionsParams::MANGLED,
                Params::EXTRA_ROUTES,
                Params::ACTIONS, // Needed to remove ?actions[]=preload, ?actions[]=loaduserstate, ?actions[]=loadlazy
            ]
        );
        $url = GeneralUtils::removeQueryArgs(
            $remove_params,
            $requestedFullURL
        );

        return urldecode($url);
    }

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
    public function getClientIPAddress(): ?string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        $serverPropertyName = $moduleConfiguration->getClientIPAddressServerPropertyName();
        return App::server($serverPropertyName);
    }
}
