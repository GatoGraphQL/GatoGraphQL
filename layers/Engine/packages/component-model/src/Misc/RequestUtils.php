<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Misc;

use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManager;
use PoP\ComponentModel\ModuleFilters\ModulePaths;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Configuration\Request;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Routing\URLParams;

class RequestUtils
{
    public static $errors = array();

    public static function getDomainId($domain)
    {
        // The domain ID is simply removing the scheme, and replacing all dots with '-'
        // It is needed to assign an extra class to the event
        $domain_id = str_replace('.', '-', removeScheme($domain));

        // Allow to override the domainId, to unify DEV and PROD domains
        return HooksAPIFacade::getInstance()->applyFilters('pop_modulemanager:domain_id', $domain_id, $domain);
    }

    public static function isSearchEngine()
    {
        return HooksAPIFacade::getInstance()->applyFilters('RequestUtils:isSearchEngine', false);
    }

    // // public static function getCheckpointConfiguration($page_id = null) {

    // //     return Settings\SettingsManagerFactory::getInstance()->getCheckpointConfiguration($page_id);
    // // }
    // public static function getCheckpoints($page_id = null) {

    //     return Settings\SettingsManagerFactory::getInstance()->getCheckpoints($page_id);
    // }

    // public static function isServerAccessMandatory($checkpoint_configuration) {

    //     // The Static type can be cached since it contains no data
    //     $dynamic_types = array(
    //         GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
    //     );
    //     $mandatory = in_array($checkpoint_configuration['type'], $dynamic_types);

    //     // Allow to add 'requires-user-state' by PoP UserState dependency
    //     return HooksAPIFacade::getInstance()->applyFilters(
    //         'RequestUtils:isServerAccessMandatory',
    //         $mandatory,
    //         $checkpoint_configuration
    //     );
    // }

    // public static function checkpointValidationRequired($checkpoint_configuration) {

    //     return true;
    //     // $type = $checkpoint_configuration['type'];
    //     // return (doingPost() && $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC) || $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER || $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATELESS;
    // }

    public static function getCurrentUrl()
    {
        // Strip the Target and Output off it, users don't need to see those
        $remove_params = (array) HooksAPIFacade::getInstance()->applyFilters(
            'RequestUtils:current_url:remove_params',
            array(
                \GD_URLPARAM_SETTINGSFORMAT,
                \GD_URLPARAM_VERSION,
                \GD_URLPARAM_TARGET,
                ModuleFilterManager::URLPARAM_MODULEFILTER,
                ModulePaths::URLPARAM_MODULEPATHS,
                \GD_URLPARAM_ACTIONPATH,
                \GD_URLPARAM_DATAOUTPUTITEMS,
                \GD_URLPARAM_DATASOURCES,
                \GD_URLPARAM_DATAOUTPUTMODE,
                \GD_URLPARAM_DATABASESOUTPUTMODE,
                \GD_URLPARAM_OUTPUT,
                \GD_URLPARAM_DATASTRUCTURE,
                Request::URLPARAM_MANGLED,
                \GD_URLPARAM_EXTRAROUTES,
                \GD_URLPARAM_ACTIONS, // Needed to remove ?actions[]=preload, ?actions[]=loaduserstate, ?actions[]=loadlazy
                \GD_URLPARAM_STRATUM,
            )
        );
        $url = GeneralUtils::removeQueryArgs($remove_params, self::getRequestedFullURL());

        // Allow plug-ins to do their own logic to the URL
        $url = HooksAPIFacade::getInstance()->applyFilters('RequestUtils:getCurrentUrl', $url);

        return urldecode($url);
    }

    public static function getFramecomponentModules()
    {
        return HooksAPIFacade::getInstance()->applyFilters(
            'RequestUtils:getFramecomponentModules',
            array()
        );
    }

    public static function addRoute($url, $route)
    {
        return GeneralUtils::addQueryArgs([URLParams::ROUTE => $route], $url);
    }

    public static function fetchingSite()
    {
        $vars = ApplicationState::getVars();
        return $vars['fetching-site'];
    }

    public static function loadingSite()
    {
        // If we are doing JSON (or any other output) AND we setting the target, then we're loading content dynamically and we need it to be JSON
        // Otherwise, it is the first time loading website => loadingSite
        $vars = ApplicationState::getVars();
        return $vars['loading-site'];
    }

    public static function isRoute($route_or_routes)
    {
        $vars = ApplicationState::getVars();
        $route = $vars['route'];
        if (is_array($route_or_routes)) {
            return in_array($route, $route_or_routes);
        }

        return $route == $route_or_routes;
    }

    /**
     * Return the requested full URL
     *
     * @param boolean $useHostRequestedByClient If true, get the host from user-provided HTTP_HOST, otherwise from the server-defined SERVER_NAME
     * @return string
     */
    public static function getRequestedFullURL(bool $useHostRequestedByClient = false): string
    {
        $s = empty($_SERVER["HTTPS"]) ? '' : (($_SERVER["HTTPS"] == "on") ? "s" : "");
        $sp = strtolower($_SERVER["SERVER_PROTOCOL"]);
        $protocol = substr($sp, 0, strpos($sp, "/")) . $s;
        /**
         * The default ports (80 for HTTP and 443 for HTTPS) must be ignored
         */
        $isDefaultPort = $s ? $_SERVER["SERVER_PORT"] == "443" : $_SERVER["SERVER_PORT"] == "80";
        $port = $isDefaultPort ? "" : (":" . $_SERVER["SERVER_PORT"]);
        /**
         * If accessing from Nginx, the server_name might point to localhost
         * instead of the actual server domain. So provide the change to use
         * the user-requested host
         *
         * @see https://stackoverflow.com/questions/2297403/what-is-the-difference-between-http-host-and-server-name-in-php
         */
        $host = $useHostRequestedByClient ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
        return $protocol . "://" . $host . $port . $_SERVER['REQUEST_URI'];
    }
}
