<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\Root\App;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Services\BasicServiceTrait;
use PoP\Definitions\Constants\Params as DefinitionsParams;

class RequestHelperService implements RequestHelperServiceInterface
{
    use BasicServiceTrait;

    public function getCurrentURL(): string
    {
        // Strip the Target and Output off it, users don't need to see those
        $remove_params = (array) App::applyFilters(
            'RequestUtils:current_url:remove_params',
            [
                Params::VERSION,
                Params::MODULEFILTER,
                Params::MODULEPATHS,
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
            $this->getRequestedFullURL()
        );

        // Allow plug-ins to do their own logic to the URL
        $url = App::applyFilters(
            'RequestUtils:getCurrentURL',
            $url
        );

        return urldecode($url);
    }

    /**
     * Return the requested full URL
     *
     * @param boolean $useHostRequestedByClient If true, get the host from user-provided HTTP_HOST, otherwise from the server-defined SERVER_NAME
     */
    public function getRequestedFullURL(bool $useHostRequestedByClient = false): string
    {
        $s = \PoP\Root\App::server("HTTPS") === "on" ? "s" : "";
        $sp = strtolower(\PoP\Root\App::server("SERVER_PROTOCOL"));
        $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
        /**
         * The default ports (80 for HTTP and 443 for HTTPS) must be ignored
         */
        $isDefaultPort = $s ? in_array(\PoP\Root\App::server("SERVER_PORT"), ["443", "80"]) : \PoP\Root\App::server("SERVER_PORT") == "80";
        $port = $isDefaultPort ? "" : (":" . \PoP\Root\App::server("SERVER_PORT"));
        /**
         * If accessing from Nginx, the server_name might point to localhost
         * instead of the actual server domain. So provide the change to use
         * the user-requested host
         *
         * @see https://stackoverflow.com/questions/2297403/what-is-the-difference-between-http-host-and-server-name-in-php
         */
        $host = $useHostRequestedByClient ? \PoP\Root\App::server('HTTP_HOST') : \PoP\Root\App::server('SERVER_NAME');
        return $protocol . "://" . $host . $port . \PoP\Root\App::server('REQUEST_URI');
    }
}
