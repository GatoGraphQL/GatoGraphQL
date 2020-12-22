<?php

declare(strict_types=1);

namespace PoPSchema\EventMutationsWPEM\TypeAPIs;

use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;
use PoPSchema\EventMutations\TypeAPIs\EventMutationTypeAPIInterface;
use EM_Event;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class EventMutationTypeAPI extends CustomPostTypeAPI implements EventMutationTypeAPIInterface
{
    public function populate(object &$event, array $post_data): void
    {
        /** @var EM_Event */
        $EM_Event = &$event;
        // Copied from function get_post($validate = true) in events-manager/classes/em-event.php
        $EM_Event->post_content = $post_data['content'];
        $EM_Event->event_name = $post_data['title'];
        $EM_Event->post_type = \EM_POST_TYPE_EVENT;

        // Comment Leo 13/03/2016: this line is MANDATORY! When it is not there, the post_except will be set as NULL,
        // and it fails to create the event, giving error "Column 'post_excerpt' cannot be null" on wp_insert_post()
        $EM_Event->post_excerpt = '';

        // Comment Leo 04/01/2018: Since EM 5.8.1.1, we must explicity set these values as below, or else saving the event fails
        $EM_Event->event_rsvp = 0;
        $EM_Event->recurrence = null;
        // $EM_Event->event_rsvp_date = null;
        $EM_Event->event_rsvp_time = null;
        $EM_Event->recurrence_days = null;

        // Comment Leo 04/01/2018: this line is MANDATORY! Since EM 5.8.1.1, if we don't add this line, we get the following PHP error when
        // executing getPostType($EM_Event) (in file `wp-content/plugins/poptheme-wassup/plugins/events-manager/pop-library/dataload/fieldresolvers/typeResolver-posts-hook.php`) after creating an event:
        // <b>Warning</b>:  array_map(): Argument #2 should be an array in <b>/Users/leo/Sites/PoP/wp-includes/post.php</b> on line <b>1980</b><br />
        $EM_Event->ancestors = array();

        // post_status might be empty (for publish)
        if ($status = $post_data['status']) {
            $EM_Event->force_status = $status;
        }

        // Copied from function get_post_meta($validate = true) in events-manager/classes/em-event.php
        // Start/End date and time
        $EM_Event->event_start_date = $post_data['when']['from'];
        $EM_Event->event_end_date = $post_data['when']['to'];

        // Location
        if ($post_data['location'] ?? null) {
            $EM_Location = \em_get_location($post_data['location'], 'post_id');
            $EM_Event->location_id = $EM_Location->location_id;
        }
        // No location
        else {
            $EM_Event->location_id = 0;
        }

        // TODO: Fix this: the "All Day" status should be selected in the Bootstrap daterange picker
        // Right now horrible fix: if fromtime and totime are both '00:00' then it's all day
        $EM_Event->event_all_day = ($post_data['when']['fromtime'] == '00:00' && $post_data['when']['totime'] == '00:00') ? 1 : 0;
        $EM_Event->event_start_time = $post_data['when']['fromtime'] . ':00';
        $EM_Event->event_end_time = $post_data['when']['totime'] . ':00';

        //Start/End times should be available as timestamp
        $EM_Event->start = strtotime($EM_Event->event_start_date . " " . $EM_Event->event_start_time);
        $EM_Event->end = strtotime($EM_Event->event_end_date . " " . $EM_Event->event_end_time);

        //Set Blog ID
        if (\is_multisite()) {
            $EM_Event->blog_id = \get_current_blog_id();
        }

        //group id
        $EM_Event->group_id = 0;
    }
}
