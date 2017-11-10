<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPThemeWassup_EM_ResourceLoader_Hooks {

    var $future_events;

    function __construct() {

        $this->future_events = array();

        add_filter(
            'PoP_ResourceLoader_FileReproduction_Config:js-resources:single',
            array($this, 'get_single_resources'),
            10,
            2
        );
        add_filter(
            'PoP_ResourceLoader_FileReproduction_Config:js-resources:page',
            array($this, 'get_page_resources'),
            10,
            2
        );
    }

    function get_page_resources($resources, $fetching_json) {
                
        // When processing POP_EM_POPPROCESSORS_PAGE_ADDLOCATION, we need a configuration for both target=main and target=modals
        // Because giving no target (the default behaviour) will then choose target=modals, below explicitly create the configuration for target=main
        $template_id = GD_TEMPLATE_TOPLEVEL_PAGE;
        $hierarchy = GD_SETTINGS_HIERARCHY_PAGE;
        $ids = array(
            POP_EM_POPPROCESSORS_PAGE_ADDLOCATION,
        ); 
        $merge = false; 
        $components = array(
            'format' => GD_TEMPLATEFORMAT_MODALS,
            'target' => GD_URLPARAM_TARGET_MAIN,
        );
        PoP_ResourceLoaderProcessorUtils::add_resources_from_current_vars($fetching_json, $resources, $template_id, $ids, $merge, $components);

        return $resources;
    }

    function get_single_resources($resources, $fetching_json) {
        
        // The event is a special case: both future and past events have different configurations
        // However, we can't tell from the URL which one it is (mesym.com/events/...)
        // So then, we gotta calculate the resources for both cases, and add them together
        // This way, loading any one event URL will load the resources needed for both situations
        // Watch out! Events with and without category POPTHEME_WASSUP_EM_CAT_EVENTLINKS have the same url path,
        // but different configuration, so we gotta select events with and without this category, and merge them all together
        $ids = array();
        $query = array(
            'scope' => 'all',
            'limit' => 2,
            'owner' => false,
            'status' => 'publish',
            'array' => true,
        );
        // API Documentation: http://wp-events-plugin.com/documentation/event-search-attributes/
        // Get Events
        if ($results = EM_Events::get(array_merge(
            $query,
            array(
                'category' => '-'.POPTHEME_WASSUP_EM_CAT_EVENTLINKS,
            )
        ))) {

            $make_future = true;
            foreach ($results as $key => $value) {
                
                $ids[] = $value['post_id'];
                if ($make_future) {

                    $this->future_events[] = $value['post_id'];
                    $make_future = false;
                }
            }
        }
        // Get Event Links
        if ($results = EM_Events::get(array_merge(
            $query,
            array(
                'category' => POPTHEME_WASSUP_EM_CAT_EVENTLINKS,
            )
        ))) {

            $make_future = true;
            foreach ($results as $key => $value) {

                $ids[] = $value['post_id'];
                if ($make_future) {

                    $this->future_events[] = $value['post_id'];
                    $make_future = false;
                }
            }
        }
        
        if ($ids) {
        
            $template_id = GD_TEMPLATE_TOPLEVEL_SINGLE;
            $hierarchy = GD_SETTINGS_HIERARCHY_SINGLE;
            $merge = true;

            // Add the hook before the execution of the method, and remove it immediately afterwards
            add_filter('em_get_event', array($this, 'force_event_scope'), PHP_INT_MAX, 2);
            PoP_ResourceLoaderProcessorUtils::add_resources_from_settingsprocessors($fetching_json, $resources, $template_id, $hierarchy/*, $blockunit_type*/, $ids, $merge);
            remove_filter('em_get_event', array($this, 'force_event_scope'), PHP_INT_MAX, 2);
        }

        return $resources;
    }

    function force_event_scope($event) {

        // Change the date for the event, make it future
        if (in_array($event->post_id, $this->future_events)) {

            // Modify start and end dates
            $event->start = POP_CONSTANT_CURRENTTIMESTAMP + 1000;
            $event->end = POP_CONSTANT_CURRENTTIMESTAMP + 2000;
        }
        // Force it to be past
        else {

            // Modify start and end dates
            $event->start = POP_CONSTANT_CURRENTTIMESTAMP - 2000;
            $event->end = POP_CONSTANT_CURRENTTIMESTAMP - 1000;
        }

        // Modify the categories, needed to get a different configuration for future/past events
        $event->categories = gd_em_event_get_categories_addtimeframecategory($event->get_categories(), $event);

        return $event;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_EM_ResourceLoader_Hooks();
