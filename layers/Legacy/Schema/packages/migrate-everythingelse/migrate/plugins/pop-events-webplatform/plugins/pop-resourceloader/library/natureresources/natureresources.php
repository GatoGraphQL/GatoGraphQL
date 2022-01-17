<?php
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Events\Facades\EventTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;

class PoP_Events_ResourceLoader_Hooks extends PoP_ResourceLoader_NatureResources_ProcessorBase
{
    public $future_events;

    public function __construct()
    {
        $this->future_events = array();
    }

    public function addSingleResources(&$resources, $modulefilter, $options)
    {

        // The event is a special case: both future and past events have different configurations
        // However, we can't tell from the URL which one it is (mesym.com/events/...)
        // So then, we gotta calculate the resources for both cases, and add them together
        // This way, loading any one event URL will load the resources needed for both situations
        $ids = array();
        $query = array(
            'scope' => 'all',
            'limit' => 2,
            'owner' => false,
            'status' => 'publish',
            // 'array' => true,
        );
        // Watch out! Events with and without category POP_EVENTLINKS_CAT_EVENTLINKS have the same url path,
        // but different configuration, so we gotta select events with and without this category, and merge them all together
        $independent_cats = \PoP\Root\App::applyFilters(
            'PoP_ApplicationProcessors_ResourceLoader_Hooks:single-resources:independent-cats',
            array()
        );
        // API Documentation: http://wp-events-plugin.com/documentation/event-search-attributes/
        // Hook in POP_EVENTLINKS_CAT_EVENTLINKS
        // To exclude category, simply make the ID negative
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        if ($results = $eventTypeAPI->getLocations(
            array_merge(
                $query,
                $independent_cats ?
                array(
                    'category' => '-'.implode(',-', $independent_cats)
                ) : array()
            ),
            [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]
        )
        ) {
            // $make_future = true;
            // foreach ($results as $key => $value) {
            //     $ids[] = $value['post_id'];
            //     if ($make_future) {
            //         $this->future_events[] = $value['post_id'];
            //         $make_future = false;
            //     }
            // }
            $ids = array_merge(
                $ids,
                $results
            );
            $this->future_events[] = $results[0];
        }
        // Get Event Links
        foreach ($independent_cats as $independent_cat) {
            if ($results = $eventTypeAPI->getLocations(
                array_merge(
                    $query,
                    array(
                        'category' => $independent_cat,
                    )
                ),
                [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]
            )
            ) {
                // $make_future = true;
                // foreach ($results as $key => $value) {
                //     $ids[] = $value['post_id'];
                //     if ($make_future) {
                //         $this->future_events[] = $value['post_id'];
                //         $make_future = false;
                //     }
                // }
                $ids = array_merge(
                    $ids,
                    $results
                );
                $this->future_events[] = $results[0];
            }
        }

        if ($ids) {
            $nature = CustomPostRequestNature::CUSTOMPOST;
            $merge = true;

            // Add the hook before the execution of the method, and remove it immediately afterwards
            \PoP\Root\App::addFilter('em_get_event', array($this, 'forceEventScope'), PHP_INT_MAX, 2);
            PoP_ResourceLoaderProcessorUtils::addResourcesFromSettingsprocessors($modulefilter, $resources, $nature, $ids, $merge, $options);
            \PoP\Root\App::removeFilter('em_get_event', array($this, 'forceEventScope'), PHP_INT_MAX, 2);
        }
    }

    public function forceEventScope($event)
    {
        // Change the date for the event, make it future
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        if (in_array($eventTypeAPI->getID($event), $this->future_events)) {
            // Modify start and end dates
            $event->start = ComponentModelComponentInfo::get('time') + 1000;
            $event->end = ComponentModelComponentInfo::get('time') + 2000;
        }
        // Force it to be past
        else {
            // Modify start and end dates
            $event->start = ComponentModelComponentInfo::get('time') - 2000;
            $event->end = ComponentModelComponentInfo::get('time') - 1000;
        }

        // Modify the categories, needed to get a different configuration for future/past events
        $event->categories = gd_em_event_get_categories_addtimeframecategory($event->get_categories(), $event);

        return $event;
    }
}

/**
 * Initialize
 */
new PoP_Events_ResourceLoader_Hooks();
