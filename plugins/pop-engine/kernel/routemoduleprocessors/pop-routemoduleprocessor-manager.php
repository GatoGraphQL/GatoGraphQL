<?php
namespace PoP\Engine;

class RouteModuleProcessor_Manager
{
    public $processors;
    
    public function __construct()
    {
        RouteModuleProcessorManager_Factory::setInstance($this);

        $this->processors = array();
    }

    public function add($processor)
    {
        foreach ($processor->getGroups() as $group) {
            $this->processors[$group] = $this->processors[$group] ?? array();
            $this->processors[$group][] = $processor;
        }
    }

    public function getProcessors($group = null)
    {
        $group = $group ?? POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE;
        return $this->processors[$group] ?? array();
    }

    public function getRouteModuleByMostAllmatchingVarsProperties($group = null/*, $route = null, $vars = null*/)
    {
        $group = $group ?? POP_PAGEMODULEGROUPPLACEHOLDER_MAINCONTENTMODULE;
        $vars = Engine_Vars::getVars();
        $nature = $vars['nature'];
        $route = $vars['route'];

        // // Allow to pass a custom $vars, with custom values
        // $vars = $vars ?? Engine_Vars::getVars();
        // $route = $route ?? Utils::getRoute();

        $processors = $this->getProcessors($group);
        $most_matching_module = false;
        $most_matching_properties_count = -1; // Start with -1, since 0 matches is possible

        foreach ($processors as $processor) {
            $nature_route_module_vars_properties = $processor->getModulesVarsPropertiesByNatureAndRoute();

            // Check if this processor implements modules for this nature and route
            if ($route_module_vars_properties = $nature_route_module_vars_properties[$nature]) {
                if ($module_vars_properties = $route_module_vars_properties[$route]) {
                    foreach ($module_vars_properties as $module => $vars_properties_items) {
                        foreach ($vars_properties_items as $vars_properties_set) {
                            // Check if the all the $vars_properties_set are satisfied <= if all those key/values are also present in $vars
                            if (arrayIsSubset($vars_properties_set, $vars)) {
                                // Check how many matches there are, and if it's the most, this is the most matching module
                                // Check that it is >= instead of >. This is done so that later processors can override the behavior from previous processors,
                                // which makes sense since plugins are loaded in a specific order
                                if (($matching_properties_count = count($vars_properties_set, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                                    $most_matching_module = $module;
                                    $most_matching_properties_count = $matching_properties_count;
                                }
                            }
                        }
                    }
                }
            }
        }

        // If there was a satisfying module, then return it
        // We can override the default module, for a specific route, by setting it to module null! Hence, here ask if the chosen module is not false,
        // and if so already return it, allowing for null values too (eg: POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES in poptheme-wassup/library/routemoduleprocessors/pagesection-maincontent.php)
        if ($most_matching_module !== false) {
            return $most_matching_module;
        }

        // Otherwise, repeat the procedure checking for one level lower: with only the nature
        foreach ($processors as $processor) {
            $nature_module_vars_properties = $processor->getModulesVarsPropertiesByNature();
            if ($module_vars_properties = $nature_module_vars_properties[$nature]) {
                foreach ($module_vars_properties as $module => $vars_properties_items) {
                    foreach ($vars_properties_items as $vars_properties_set) {
                        // Check if the all the $vars_properties are satisfied <= if all those key/values are also present in $vars
                        if (arrayIsSubset($vars_properties_set, $vars)) {
                            // Check how many matches there are, and if it's the most, this is the most matching module
                            if (($matching_properties_count = count($vars_properties_set, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                                $most_matching_module = $module;
                                $most_matching_properties_count = $matching_properties_count;
                            }
                        }
                    }
                }
            }
        }

        if ($most_matching_module !== false) {
            return $most_matching_module;
        }

        // Finally, check without nature or route
        foreach ($processors as $processor) {
            if ($module_vars_properties = $processor->getModulesVarsProperties()) {
                foreach ($module_vars_properties as $module => $vars_properties_items) {
                    foreach ($vars_properties_items as $vars_properties_set) {
                        // Check if the all the $vars_properties are satisfied <= if all those key/values are also present in $vars
                        if (arrayIsSubset($vars_properties_set, $vars)) {
                            // Check how many matches there are, and if it's the most, this is the most matching module
                            if (($matching_properties_count = count($vars_properties_set, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                                $most_matching_module = $module;
                                $most_matching_properties_count = $matching_properties_count;
                            }
                        }
                    }
                }
            }
        }

        // If it is false, then return null
        return $most_matching_module ? $most_matching_module : null;
    }
}

/**
 * Initialization
 */
new RouteModuleProcessor_Manager();
