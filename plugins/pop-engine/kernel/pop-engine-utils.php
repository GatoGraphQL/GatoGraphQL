<?php
namespace PoP\Engine;

class Utils
{
    public static $errors = array();
    
    public static function getDomainId($domain)
    {

        // The domain ID is simply removing the scheme, and replacing all dots with '-'
        // It is needed to assign an extra class to the event
        $domain_id = str_replace('.', '-', removeScheme($domain));

        // Allow to override the domainId, to unify DEV and PROD domains
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('pop_modulemanager:domain_id', $domain_id, $domain);
    }

    public static function isSearchEngine()
    {
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('\PoP\Engine\Utils:isSearchEngine', false);
    }

    // // public static function getCheckpointConfiguration($page_id = null) {

    // //     return Settings\SettingsManager_Factory::getInstance()->getCheckpointConfiguration($page_id);
    // // }
    // public static function getCheckpoints($page_id = null) {

    //     return Settings\SettingsManager_Factory::getInstance()->getCheckpoints($page_id);
    // }

    // public static function isServerAccessMandatory($checkpoint_configuration) {

    //     // The Static type can be cached since it contains no data
    //     $dynamic_types = array(
    //         GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER,
    //     );
    //     $mandatory = in_array($checkpoint_configuration['type'], $dynamic_types);

    //     // Allow to add 'requires-user-state' by PoP UserState dependency
    //     return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
    //         '\PoP\Engine\Utils:isServerAccessMandatory',
    //         $mandatory,
    //         $checkpoint_configuration
    //     );
    // }

    // public static function checkpointValidationRequired($checkpoint_configuration) {

    //     return true;
    //     // $type = $checkpoint_configuration['type'];
    //     // return (doingPost() && $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATIC) || $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_DATAFROMSERVER || $type == GD_DATALOAD_VALIDATECHECKPOINTS_TYPE_STATELESS;
    // }

    public static function limitResults($results)
    {

        // Cut results if more than 4 times the established limit. This is to protect from hackers adding all post ids.
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $limit = 4 * $cmsapi->getOption(\PoP\CMS\NameResolver_Factory::getInstance()->getName('popcms:option:limit'));
        if (count($results) > $limit) {
            array_splice($results, $limit);
        }

        return $results;
    }

    public static function getRouteURL($route)
    {
        // For the route, the ID is the URI applied on the homeURL instead of the domain
        // (then, the id for domain.com/en/slug/ is "slug" and not "en/slug")
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $homeurl = $cmsapi->getHomeURL();
        return $homeurl.$route.'/';
    }
    public static function getRouteTitle($route)
    {
        $title = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            'route:title',
            $route,
            $route
        );
        return $title;
    }
    public static function getCurrentPath()
    {
        // For the route, the ID is the URI applied on the homeURL instead of the domain
        // (then, the id for domain.com/en/slug/ is "slug" and not "en/slug")
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        $homeurl = $cmsapi->getHomeURL();
        $path = substr(self::getCurrentUrl(), strlen($homeurl));
        $params_pos = strpos($path, '?');
        if ($params_pos !== false) {
            $path = substr($path, 0, $params_pos);
        }
        return trim($path, '/');
    }
    public static function getCurrentUrl()
    {

        // Strip the Target and Output off it, users don't need to see those
        $remove_params = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            '\PoP\Engine\Utils:current_url:remove_params',
            array(
                GD_URLPARAM_SETTINGSFORMAT,
                GD_URLPARAM_VERSION,
                GD_URLPARAM_TARGET,
                GD_URLPARAM_MODULEFILTER,
                GD_URLPARAM_MODULEPATHS,
                GD_URLPARAM_HEADMODULE,
                GD_URLPARAM_ACTIONPATH,
                GD_URLPARAM_DATAOUTPUTITEMS,
                GD_URLPARAM_DATASOURCES,
                GD_URLPARAM_DATAOUTPUTMODE,
                GD_URLPARAM_DATABASESOUTPUTMODE,
                GD_URLPARAM_OUTPUT,
                GD_URLPARAM_DATASTRUCTURE,
                GD_URLPARAM_MANGLED,
                GD_URLPARAM_EXTRAROUTES,
                GD_URLPARAM_ACTION, // Needed to remove ?action=preload, ?action=loaduserstate, ?action=loadlazy
            )
        );
        $cmshelpers = \PoP\CMS\HelperAPI_Factory::getInstance();
        $url = $cmshelpers->removeQueryArgs($remove_params, fullUrl());

        // Allow plug-ins to do their own logic to the URL
        $url = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('\PoP\Engine\Utils:getCurrentUrl', $url);

        return urldecode($url);
    }

    public static function getFramecomponentModules()
    {
        return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('\PoP\Engine\Utils:getFramecomponentModules', array());
    }

    public static function addRoute($url, $route)
    {
        $cmshelpers = \PoP\CMS\HelperAPI_Factory::getInstance();
        return $cmshelpers->addQueryArgs([GD_URLPARAM_ROUTE => $route], $url);
    }

    public static function getPagePath($page_id)
    {

        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();

        // Generate the page path. Eg: http://mesym.com/events/past/ will render events/past
        $permalink = $cmsapi->getPermalink($page_id);

        $domain = $cmsapi->getHomeURL();

        // Remove the domain from the permalink => page path
        $page_path = substr($permalink, strlen($domain));

        // Remove the first and last '/'
        $page_path = trim($page_path, '/');

        return $page_path;
    }

    // public static function getPageUri($page_id)
    // {

    //     $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    //     // Generate the page URI. Eg: http://mesym.com/en/events/past/ will render /en/events/past/
    //     $permalink = $cmsapi->getPermalink($page_id);
    //     $domain = $cmsapi->getSiteURL();

    //     // Remove the domain from the permalink => page path
    //     $page_uri = substr($permalink, strlen($domain));

    //     return $page_uri;
    // }

    // public static function getNaturePageId()
    // {
    //     $vars = Engine_Vars::getVars();
    //     $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
    //     $nature = $vars['nature'];
    //     if ($vars['routing-state']['is-page']) {
    //         $page_id = $vars['routing-state']['queried-object-id'];
    //     } elseif ($vars['routing-state']['is-home']/* || $vars['routing-state']['is-front-page']*/) {
    //         $page_id = self::getNatureDefaultPage($nature);
    //     } elseif ($vars['routing-state']['is-author'] || $vars['routing-state']['is-single'] || $vars['routing-state']['is-tag']) {
    //         // Get the page from the tab attr
    //         if ($tab = $vars['tab']) {
    //             $page_id = $cmsapi->getPageIdByPath($tab);
    //         }
    //         // Otherwise, get the default page for each nature
    //         else {
    //             $page_id = self::getNatureDefaultPage($nature);
    //         }
    //     } elseif ($vars['routing-state']['is-404']) {
    //         $page_id = self::getNatureDefaultPage($nature);
    //     }
    //     // Comment Leo 12/04/2017: there is a problem, in which calling
    //     // https://www.mesym.com/en/stories/ attempts to load the "stories" category template
    //     // (even though stories is located under category "posts"). When that happens, since
    //     // PoP currently doesn't support categories, then simply treat it as a 404
    //     elseif ($vars['routing-state']['is-category']/* || $vars['routing-state']['is-archive']*/) {
    //         $page_id = self::getNatureDefaultPage($nature);
    //     } else {
    //         // Route is the default case
    //         $page_id = \PoP\Engine\Utils::getCurrentPath();
    //     }

    //     return $page_id;
    // }
    // public static function getRoute()
    // {
    //     $vars = Engine_Vars::getVars();
    //     return $vars['route'];
    //     // if ($route = $vars['route']) {
    //     //     return $route;
    //     // }

    //     // return \PoP\Engine\Utils::getCurrentPath();
    // }

    public static function getDatastructureFormatter()
    {
        $vars = Engine_Vars::getVars();

        $datastructureformat_manager = DataStructureFormat_Manager_Factory::getInstance();
        return $datastructureformat_manager->getDatastructureFormatter($vars['datastructure']);
    }

    public static function fetchingSite()
    {
        $vars = Engine_Vars::getVars();
        return $vars['fetching-site'];
    }

    public static function loadingSite()
    {

        // If we are doing JSON (or any other output) AND we setting the target, then we're loading content dynamically and we need it to be JSON
        // Otherwise, it is the first time loading website => loadingSite
        $vars = Engine_Vars::getVars();
        return $vars['loading-site'];
    }

    public static function isRoute($route_or_routes)
    {
        $vars = Engine_Vars::getVars();
        $route = $vars['route'];
        if (is_array($route_or_routes)) {
            return in_array($route, $route_or_routes);
        }

        return $route == $route_or_routes;
    }
}
