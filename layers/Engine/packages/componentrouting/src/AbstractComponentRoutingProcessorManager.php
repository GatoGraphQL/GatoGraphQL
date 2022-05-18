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
    public function getRoutingComponentByMostAllMatchingStateProperties(string $group = null): ?array
    {
        $group ??= $this->getDefaultGroup();
        $nature = App::getState('nature');
        $route = App::getState('route');
        $appState = App::getAppStateManager()->all();

        $processors = $this->getProcessors($group);
        $most_matching_component = false;
        $most_matching_properties_count = -1; // Start with -1, since 0 matches is possible

        foreach ($processors as $processor) {
            $nature_route_state_properties = $processor->getStatePropertiesToSelectComponentByNatureAndRoute();

            // Check if this processor implements components for this nature and route
            if ($route_state_properties = $nature_route_state_properties[$nature] ?? null) {
                if ($state_properties = $route_state_properties[$route] ?? null) {
                    foreach ($state_properties as $state_properties_set) {
                        // Check if the all the $state_properties_set are satisfied <= if all those key/values are also present in the application state
                        $conditions = $state_properties_set['conditions'] ?? [];
                        if (Methods::arrayIsSubset($conditions, $appState)) {
                            // Check how many matches there are, and if it's the most, this is the most matching component
                            // Check that it is >= instead of >. This is done so that later processors can override the behavior from previous processors,
                            // which makes sense since plugins are loaded in a specific order
                            if (($matching_properties_count = count($conditions, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                                $most_matching_component = $state_properties_set['component'];
                                $most_matching_properties_count = $matching_properties_count;
                            }
                        }
                    }
                }
            }
        }

        // If there was a satisfying component, then return it
        // We can override the default component, for a specific route, by setting it to component null! Hence, here ask if the chosen component is not false,
        // and if so already return it, allowing for null values too (eg: POPTHEME_WASSUP_ROUTE_LOADERS_INITIALFRAMES in poptheme-wassup/library/componentroutingprocessors/pagesection-maincontent.php)
        if ($most_matching_component !== false) {
            return $most_matching_component;
        }

        // Otherwise, repeat the procedure checking for one level lower: with only the nature
        foreach ($processors as $processor) {
            $nature_state_properties = $processor->getStatePropertiesToSelectComponentByNature();
            if ($state_properties = $nature_state_properties[$nature] ?? null) {
                foreach ($state_properties as $state_properties_set) {
                    // Check if the all the $state_properties are satisfied <= if all those key/values are also present in the application state
                    $conditions = $state_properties_set['conditions'] ?? [];
                    if (Methods::arrayIsSubset($conditions, $appState)) {
                        // Check how many matches there are, and if it's the most, this is the most matching component
                        if (($matching_properties_count = count($conditions, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                            $most_matching_component = $state_properties_set['component'];
                            $most_matching_properties_count = $matching_properties_count;
                        }
                    }
                }
            }
        }

        if ($most_matching_component !== false) {
            return $most_matching_component;
        }

        // Finally, check without nature or route
        foreach ($processors as $processor) {
            if ($state_properties = $processor->getStatePropertiesToSelectComponent()) {
                foreach ($state_properties as $state_properties_set) {
                    // Check if the all the $state_properties are satisfied <= if all those key/values are also present in the application state
                    $conditions = $state_properties_set['conditions'] ?? [];
                    if (Methods::arrayIsSubset($conditions, $appState)) {
                        // Check how many matches there are, and if it's the most, this is the most matching component
                        if (($matching_properties_count = count($conditions, COUNT_RECURSIVE)) >= $most_matching_properties_count) {
                            $most_matching_component = $state_properties_set['component'];
                            $most_matching_properties_count = $matching_properties_count;
                        }
                    }
                }
            }
        }

        // If it is false, then return null
        return $most_matching_component ? (array)$most_matching_component : null;
    }
}
