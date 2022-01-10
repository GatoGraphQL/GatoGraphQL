<?php

declare(strict_types=1);

namespace PoP\ModuleRouting;

use PoP\ModuleRouting\Helpers\Methods;
use PoP\Root\App;

abstract class AbstractRouteModuleProcessorManager implements RouteModuleProcessorManagerInterface
{
    /**
     * @var array<string, AbstractRouteModuleProcessor[]>
     */
    protected array $processors = [];

    public function addRouteModuleProcessor(AbstractRouteModuleProcessor $processor): void
    {
        foreach ($processor->getGroups() as $group) {
            $this->processors[$group] ??= [];
            $this->processors[$group][] = $processor;
        }
    }

    /**
     * @return AbstractRouteModuleProcessor[]
     */
    public function getProcessors(string $group = null): array
    {
        $group ??= $this->getDefaultGroup();
        return $this->processors[$group] ?? array();
    }

    public function getDefaultGroup(): string
    {
        return ModuleRoutingGroups::ENTRYMODULE;
    }

    /**
     * @return string[]|null
     */
    public function getRouteModuleByMostAllmatchingVarsProperties(string $group = null): ?array
    {
        $group ??= $this->getDefaultGroup();
        $nature = \PoP\Root\App::getState('nature');
        $route = \PoP\Root\App::getState('route');

        $processors = $this->getProcessors($group);
        $most_matching_module = false;
        $most_matching_properties_count = -1; // Start with -1, since 0 matches is possible

        foreach ($processors as $processor) {
            $nature_route_vars_properties = $processor->getModulesVarsPropertiesByNatureAndRoute();

            // Check if this processor implements modules for this nature and route
            if ($route_vars_properties = $nature_route_vars_properties[$nature] ?? null) {
                if ($vars_properties = $route_vars_properties[$route] ?? null) {
                    foreach ($vars_properties as $vars_properties_set) {
                        // Check if the all the $vars_properties_set are satisfied <= if all those key/values are also present in the application state
                        $conditions = $vars_properties_set['conditions'] ?? [];
                        if (Methods::arrayIsSubset($conditions, App::getAppStateManager()->all())) {
                            // Check how many matches there are, and if it's the most, this is the most matching module
                            // Check that it is >= instead of >. This is done so that later processors can override the behavior from previous processors,
                            // which makes sense since plugins are loaded in a specific order
                            if (($matching_properties_count = count($conditions, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                                $most_matching_module = $vars_properties_set['module'];
                                $most_matching_properties_count = $matching_properties_count;
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
            $nature_vars_properties = $processor->getModulesVarsPropertiesByNature();
            if ($vars_properties = $nature_vars_properties[$nature] ?? null) {
                foreach ($vars_properties as $vars_properties_set) {
                    // Check if the all the $vars_properties are satisfied <= if all those key/values are also present in the application state
                    $conditions = $vars_properties_set['conditions'] ?? [];
                    if (Methods::arrayIsSubset($conditions, App::getAppStateManager()->all())) {
                        // Check how many matches there are, and if it's the most, this is the most matching module
                        if (($matching_properties_count = count($conditions, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                            $most_matching_module = $vars_properties_set['module'];
                            $most_matching_properties_count = $matching_properties_count;
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
            if ($vars_properties = $processor->getModulesVarsProperties()) {
                foreach ($vars_properties as $vars_properties_set) {
                    // Check if the all the $vars_properties are satisfied <= if all those key/values are also present in the application state
                    $conditions = $vars_properties_set['conditions'] ?? [];
                    if (Methods::arrayIsSubset($conditions, App::getAppStateManager()->all())) {
                        // Check how many matches there are, and if it's the most, this is the most matching module
                        if (($matching_properties_count = count($conditions, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                            $most_matching_module = $vars_properties_set['module'];
                            $most_matching_properties_count = $matching_properties_count;
                        }
                    }
                }
            }
        }

        // If it is false, then return null
        return $most_matching_module ? (array)$most_matching_module : null;
    }
}
