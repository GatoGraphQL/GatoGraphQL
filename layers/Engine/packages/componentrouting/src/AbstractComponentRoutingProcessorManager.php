<?php

declare(strict_types=1);

namespace PoP\ComponentRouting;

use PoP\ComponentRouting\Helpers\Methods;
use PoP\Root\App;

abstract class AbstractComponentRoutingProcessorManager implements ComponentRoutingProcessorManagerInterface
{
    /**
     * @var array<string, AbstractComponentRoutingProcessor[]>
     */
    protected array $processors = [];

    public function addComponentRoutingProcessor(AbstractComponentRoutingProcessor $processor): void
    {
        foreach ($processor->getGroups() as $group) {
            $this->processors[$group] ??= [];
            $this->processors[$group][] = $processor;
        }
    }

    /**
     * @return AbstractComponentRoutingProcessor[]
     */
    public function getProcessors(string $group = null): array
    {
        $group ??= $this->getDefaultGroup();
        return $this->processors[$group] ?? array();
    }

    public function getDefaultGroup(): string
    {
        return ComponentRoutingGroups::ENTRYCOMPONENT;
    }

    /**
     * @return string[]|null
     */
    public function getRouteModuleByMostAllmatchingVarsProperties(string $group = null): ?array
    {
        $group ??= $this->getDefaultGroup();
        $nature = App::getState('nature');
        $route = App::getState('route');
        $appState = App::getAppStateManager()->all();

        $processors = $this->getProcessors($group);
        $most_matching_module = false;
        $most_matching_properties_count = -1; // Start with -1, since 0 matches is possible

        foreach ($processors as $processor) {
            $nature_route_vars_properties = $processor->getStatePropertiesToSelectComponentByNatureAndRoute();

            // Check if this processor implements modules for this nature and route
            if ($route_vars_properties = $nature_route_vars_properties[$nature] ?? null) {
                if ($vars_properties = $route_vars_properties[$route] ?? null) {
                    foreach ($vars_properties as $vars_properties_set) {
                        // Check if the all the $vars_properties_set are satisfied <= if all those key/values are also present in the application state
                        $conditions = $vars_properties_set['conditions'] ?? [];
                        if (Methods::arrayIsSubset($conditions, $appState)) {
                            // Check how many matches there are, and if it's the most, this is the most matching module
                            // Check that it is >= instead of >. This is done so that later processors can override the behavior from previous processors,
                            // which makes sense since plugins are loaded in a specific order
                            if (($matching_properties_count = count($conditions, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                                $most_matching_module = $vars_properties_set['component'];
                                $most_matching_properties_count = $matching_properties_count;
                            }
                        }
                    }
                }
            }
        }

        // If there was a satisfying module, then return it
        // We can override the default module, for a specific route, by setting it to module null! Hence, here ask if the chosen module is not false,
        // and if so already return it, allowing for null values too (eg: POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES in poptheme-wassup/library/componentroutingprocessors/pagesection-maincontent.php)
        if ($most_matching_module !== false) {
            return $most_matching_module;
        }

        // Otherwise, repeat the procedure checking for one level lower: with only the nature
        foreach ($processors as $processor) {
            $nature_vars_properties = $processor->getStatePropertiesToSelectComponentByNature();
            if ($vars_properties = $nature_vars_properties[$nature] ?? null) {
                foreach ($vars_properties as $vars_properties_set) {
                    // Check if the all the $vars_properties are satisfied <= if all those key/values are also present in the application state
                    $conditions = $vars_properties_set['conditions'] ?? [];
                    if (Methods::arrayIsSubset($conditions, $appState)) {
                        // Check how many matches there are, and if it's the most, this is the most matching module
                        if (($matching_properties_count = count($conditions, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                            $most_matching_module = $vars_properties_set['component'];
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
            if ($vars_properties = $processor->getStatePropertiesToSelectComponent()) {
                foreach ($vars_properties as $vars_properties_set) {
                    // Check if the all the $vars_properties are satisfied <= if all those key/values are also present in the application state
                    $conditions = $vars_properties_set['conditions'] ?? [];
                    if (Methods::arrayIsSubset($conditions, $appState)) {
                        // Check how many matches there are, and if it's the most, this is the most matching module
                        if (($matching_properties_count = count($conditions, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                            $most_matching_module = $vars_properties_set['component'];
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
