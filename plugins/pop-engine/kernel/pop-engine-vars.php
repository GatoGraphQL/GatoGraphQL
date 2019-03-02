<?php
namespace PoP\Engine;

class Engine_Vars
{
    public static $vars = array();
    public static $query;

    public static function reset()
    {
        self::$vars = array();

        // Allow WP to set the new $query
        \PoP\CMS\HooksAPI_Factory::getInstance()->doAction('\PoP\Engine\Engine_Vars:reset');
    }

    public static function setQuery($query)
    {
        self::$query = $query;
    }

    public static function getModulepaths()
    {
        $ret = array();
        if ($paths = $_REQUEST[GD_URLPARAM_MODULEPATHS]) {
            if (!is_array($paths)) {
                $paths = array($paths);
            }

            // If any path is a substring from another one, then it is its root, and only this one will be taken into account, so remove its substrings
            // Eg: toplevel.pagesection-top is substring of toplevel, so if passing these 2 modulepaths, keep only toplevel
            // Check that the last character is ".", to avoid toplevel1 to be removed
            $paths = array_filter(
                $paths,
                function ($item) use ($paths) {
                    foreach ($paths as $path) {
                        if (strlen($item) > strlen($path) && strpos($item, $path) === 0 && $item[strlen($path)] == POP_CONSTANT_MODULESTARTPATH_SEPARATOR) {
                            return false;
                        }
                    }

                    return true;
                }
            );

            foreach ($paths as $path) {
                   // Each path must be converted to an array of the modules
                $ret[] = ModulePathManager_Utils::recastModulePath($path);
            }
        }

        return $ret;
    }

    public static function getHierarchy($query)
    {
        $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
        
        // Check all the available hierarchies, and analyze the query against each.
        // The first one that matches, then that's the hierarchy
        foreach (HierarchyManager::getHierarchies() as $maybe_hierarchy) {
            if ($cmsapi->queryIsHierarchy($query, $maybe_hierarchy)) {
                return $maybe_hierarchy;
            }
        }

        // Default one
        return GD_SETTINGS_HIERARCHY_PAGE;
    }

    public static function getQueryObject($query)
    {

        // If there's no query, set the global one
        if (!$query) {
            $cmsapi = \PoP\CMS\FunctionAPI_Factory::getInstance();
            return $cmsapi->getGlobalQuery();
        }

        return $query;
    }

    public static function getVars()
    {
        if (self::$vars) {
            return self::$vars;
        }

        // From the query object we are able to obtain the hierarchy for the current request. Based on the global $wp_query object
        self::$query = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            '\PoP\Engine\Engine_Vars:query',
            self::getQueryObject(self::$query)
        );

        // The hierarchy is a concept taken from WordPress. It depends on the structure of the URL
        // By default is a page, since everything is a page unless the URL suits a more specialized hierarchy
        $hierarchy = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            '\PoP\Engine\Engine_Vars:hierarchy',
            self::getHierarchy(self::$query)
        );

        // Convert them to lower to make it insensitive to upper/lower case values
        $output = strtolower($_REQUEST[GD_URLPARAM_OUTPUT]);
        $dataoutputitems = $_REQUEST[GD_URLPARAM_DATAOUTPUTITEMS];
        $datasources = strtolower($_REQUEST[GD_URLPARAM_DATASOURCES]);
        $datastructure = strtolower($_REQUEST[GD_URLPARAM_DATASTRUCTURE]);
        $dataoutputmode = strtolower($_REQUEST[GD_URLPARAM_DATAOUTPUTMODE]);
        $dboutputmode = strtolower($_REQUEST[GD_URLPARAM_DATABASESOUTPUTMODE]);
        $target = strtolower($_REQUEST[GD_URLPARAM_TARGET]);
        $mangled = Server\Utils::isMangled() ? '' : GD_URLPARAM_MANGLED_NONE;
        $tab = strtolower($_REQUEST[GD_URLPARAM_TAB]);
        $action = strtolower($_REQUEST[GD_URLPARAM_ACTION]);

        $outputs = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            '\PoP\Engine\Engine_Vars:outputs',
            array(
                GD_URLPARAM_OUTPUT_HTML,
                GD_URLPARAM_OUTPUT_JSON,
            )
        );
        if (!in_array($output, $outputs)) {
            $output = GD_URLPARAM_OUTPUT_HTML;
        }

        // Target/Module default values (for either empty, or if the user is playing around with the url)
        $alldatasources = array(
            GD_URLPARAM_DATASOURCES_ONLYMODEL,
            GD_URLPARAM_DATASOURCES_MODELANDREQUEST,
        );
        if (!in_array($datasources, $alldatasources)) {
            $datasources = GD_URLPARAM_DATASOURCES_MODELANDREQUEST;
        }

        $dataoutputmodes = array(
            GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES,
            GD_URLPARAM_DATAOUTPUTMODE_COMBINED,
        );
        if (!in_array($dataoutputmode, $dataoutputmodes)) {
            $dataoutputmode = GD_URLPARAM_DATAOUTPUTMODE_SPLITBYSOURCES;
        }

        $dboutputmodes = array(
            GD_URLPARAM_DATABASESOUTPUTMODE_SPLITBYDATABASES,
            GD_URLPARAM_DATABASESOUTPUTMODE_COMBINED,
        );
        if (!in_array($dboutputmode, $dboutputmodes)) {
            $dboutputmode = GD_URLPARAM_DATABASESOUTPUTMODE_SPLITBYDATABASES;
        }

        if ($dataoutputitems) {
            if (!is_array($dataoutputitems)) {
                $dataoutputitems = explode(POP_CONSTANT_PARAMVALUE_SEPARATOR, strtolower($dataoutputitems));
            } else {
                $dataoutputitems = array_map('strtolower', $dataoutputitems);
            }
        }
        $alldataoutputitems = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            '\PoP\Engine\Engine_Vars:dataoutputitems',
            array(
                GD_URLPARAM_DATAOUTPUTITEMS_META,
                GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS,
                GD_URLPARAM_DATAOUTPUTITEMS_DATASETMODULESETTINGS,
                GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA,
                GD_URLPARAM_DATAOUTPUTITEMS_DATABASES,
                GD_URLPARAM_DATAOUTPUTITEMS_SESSION,
            )
        );
        $dataoutputitems = array_intersect(
            $dataoutputitems ?? array(),
            $alldataoutputitems
        );
        if (!$dataoutputitems) {
            $dataoutputitems = array(
                GD_URLPARAM_DATAOUTPUTITEMS_META,
                GD_URLPARAM_DATAOUTPUTITEMS_MODULESETTINGS,
                GD_URLPARAM_DATAOUTPUTITEMS_MODULEDATA,
                GD_URLPARAM_DATAOUTPUTITEMS_DATABASES,
                GD_URLPARAM_DATAOUTPUTITEMS_SESSION,
            );
        }
        
        // If not target, or invalid, reset it to "main"
        // We allow an empty target if none provided, so that we can generate the settings cache when no target is provided
        // (ie initial load) and when target is provided (ie loading pageSection)
        $targets = \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters(
            '\PoP\Engine\Engine_Vars:targets',
            array(
                POP_TARGET_MAIN,
            )
        );
        if (!in_array($target, $targets)) {
            $target = POP_TARGET_MAIN;
        }
        
        $modulefilter_manager = ModuleFilterManager_Factory::getInstance();
        $modulefilter = $modulefilter_manager->getSelectedFilterName();
        
        $fetchingSite = is_null($modulefilter);
        $loadingSite = $fetchingSite && $output == GD_URLPARAM_OUTPUT_HTML;

        // Format: if not set, then use the default one: "settings-format" can be defined at the settings, and persists as the default option
        // Use 'format' the first time to set the 'settingsformat' value. So if "loadingSite()" is true, take the value from 'format'
        if ($loadingSite) {
            $settingsformat = strtolower($_REQUEST[GD_URLPARAM_FORMAT]);
        } else {
            $settingsformat = strtolower($_REQUEST[GD_URLPARAM_SETTINGSFORMAT]);
        }
        $format = isset($_REQUEST[GD_URLPARAM_FORMAT]) ? strtolower($_REQUEST[GD_URLPARAM_FORMAT]) : $settingsformat;
        // Comment Leo 13/11/2017: If there is not format, then set it to 'default'
        // This is needed so that the /generate/ generated configurations under a $model_instance_id (based on the value of $vars)
        // can match the same $model_instance_id when visiting that page
        if (!$format) {
            $format = POP_VALUES_DEFAULT;
        }
        self::$vars = array(
            'hierarchy' => $hierarchy,
            'output' => $output,
            'modulefilter' => $modulefilter,
            'actionpath' => $_REQUEST[GD_URLPARAM_ACTIONPATH],
            'target' => $target,
            'dataoutputitems' => $dataoutputitems,
            'datasources' => $datasources,
            'datastructure' => $datastructure,
            'dataoutputmode' => $dataoutputmode,
            'dboutputmode' => $dboutputmode,
            'mangled' => $mangled,
            'format' => $format,
            'settingsformat' => $settingsformat,
            'tab' => $tab,
            'action' => $action,
            'loading-site' => $loadingSite,
            'fetching-site' => $fetchingSite,
        );

        if ($modulefilter == POP_MODULEFILTER_MODULEPATHS) {
            self::$vars['modulepaths'] = self::getModulepaths();
        } elseif ($modulefilter == POP_MODULEFILTER_HEADMODULE) {
            self::$vars['headmodule'] = $_REQUEST[GD_URLPARAM_HEADMODULE];
        }
        

        if (Server\Utils::enableConfigByParams()) {
            self::$vars['config'] = $_REQUEST[POP_URLPARAM_CONFIG];
        }

        // The global state below, will need to be hooked in by pop-application
        self::calculateAndSetVarsState(true);

        // Allow for plug-ins to add their own vars
        \PoP\CMS\HooksAPI_Factory::getInstance()->doAction(
            '\PoP\Engine\Engine_Vars:addVars',
            array(&self::$vars),
            self::$query
        );

        return self::$vars;
    }

    public static function setHierarchyInGlobalState()
    {
        $hierarchy = self::$vars['hierarchy'];
        self::$vars['global-state']['is-page'] = $hierarchy == GD_SETTINGS_HIERARCHY_PAGE;
        self::$vars['global-state']['is-home'] = $hierarchy == GD_SETTINGS_HIERARCHY_HOME;
        self::$vars['global-state']['is-tag'] = $hierarchy == GD_SETTINGS_HIERARCHY_TAG;
        self::$vars['global-state']['is-single'] = $hierarchy == GD_SETTINGS_HIERARCHY_SINGLE;
        self::$vars['global-state']['is-author'] = $hierarchy == GD_SETTINGS_HIERARCHY_AUTHOR;
        self::$vars['global-state']['is-404'] = $hierarchy == GD_SETTINGS_HIERARCHY_404;
        self::$vars['global-state']['is-front-page'] = $hierarchy == GD_SETTINGS_HIERARCHY_FRONTPAGE;
        self::$vars['global-state']['is-search'] = $hierarchy == GD_SETTINGS_HIERARCHY_SEARCH;
        self::$vars['global-state']['is-category'] = $hierarchy == GD_SETTINGS_HIERARCHY_CATEGORY;
        self::$vars['global-state']['is-archive'] = $hierarchy == GD_SETTINGS_HIERARCHY_ARCHIVE;
    }

    public static function calculateAndSetVarsState($reset = true)
    {
        $hierarchy = self::$vars['hierarchy'];

        // Reset will set the queried object from $query. By default it's true, but when calculating the resources for the resourceloader, in which the queried-object is set manually, it must be false
        if ($reset) {
            self::$vars['global-state'] = array();
            self::setHierarchyInGlobalState();

            \PoP\CMS\HooksAPI_Factory::getInstance()->doAction(
                '\PoP\Engine\Engine_Vars:calculateAndSetVarsState:reset',
                array(&self::$vars),
                self::$query
            );
        }

        \PoP\CMS\HooksAPI_Factory::getInstance()->doAction(
            '\PoP\Engine\Engine_Vars:calculateAndSetVarsState',
            array(&self::$vars),
            self::$query
        );

        // Function `getPageModuleByMostAllmatchingVarsProperties` actually needs to access all values in $vars
        // Hence, calculate only at the very end
        if ($reset) {
            // If filtering module by "maincontent", then calculate which is the main content module
            if (self::$vars['modulefilter'] == POP_MODULEFILTER_MAINCONTENTMODULE) {
                $pop_module_pagemoduleprocessor_manager = PageModuleProcessorManager_Factory::getInstance();
                self::$vars['maincontentmodule'] = $pop_module_pagemoduleprocessor_manager->getPageModuleByMostAllmatchingVarsProperties(POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE);
            }
        }
    }
}
