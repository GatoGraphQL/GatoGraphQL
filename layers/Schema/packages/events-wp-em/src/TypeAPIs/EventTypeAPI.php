<?php

declare(strict_types=1);

namespace PoPSchema\EventsWPEM\TypeAPIs;

use WP_Post;
use EM_Event;
use EM_Events;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;
use PoPSchema\Events\TypeAPIs\EventTypeAPIInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoP\ComponentModel\TypeDataResolvers\APITypeDataResolverTrait;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

use function date_i18n;
use function em_get_event;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class EventTypeAPI extends CustomPostTypeAPI implements EventTypeAPIInterface
{
    use APITypeDataResolverTrait;

    /**
     * Return the event's ID
     */
    public function getID(object $event): string | int
    {
        return $event->post_id;
    }

    /**
     * Indicates if the passed object is of type Event
     */
    public function isInstanceOfEventType(object $object): bool
    {
        return
            $object instanceof EM_Event ||
            (
                ($object instanceof WP_Post) &&
                $object->post_type == \EM_POST_TYPE_EVENT
            );
    }

    /**
     * Get the event with provided ID or, if it doesn't exist, null
     * The provided ID is that for the wp_posts table, not em_events
     * (it is `post_id`, not `event_id`)
     */
    public function getEvent(int | string $customPostID): ?object
    {
        $event = em_get_event($customPostID, 'post_id');
        // If passing the ID of a post (not an event) function `em_get_event`
        // still returns an object of class `EM_Event`
        // Make sure it is an event by checking if it has an event_id
        if (!$event->event_id) {
            return null;
        }
        return $event;
    }

    /**
     * Indicate if an event with provided ID exists
     */
    public function eventExists(int | string $id): bool
    {
        return $this->getEvent($id) != null;
    }

    protected function getEventFromObjectOrId($post_or_post_id)
    {
        return is_object($post_or_post_id) ? $post_or_post_id : em_get_event($post_or_post_id, 'post_id');
    }

    public function isFutureEvent($post_or_post_id): bool
    {
        $EM_Event = $this->getEventFromObjectOrId($post_or_post_id);
        return \POP_CONSTANT_TIME < $EM_Event->start;
    }

    public function isCurrentEvent($post_or_post_id): bool
    {
        $EM_Event = $this->getEventFromObjectOrId($post_or_post_id);
        return $EM_Event->start <= \POP_CONSTANT_TIME && \POP_CONSTANT_TIME < $EM_Event->end;
    }

    public function isPastEvent($post_or_post_id): bool
    {
        $EM_Event = $this->getEventFromObjectOrId($post_or_post_id);
        return $EM_Event->end < \POP_CONSTANT_TIME;
    }

    public function getEvents($query = array(), array $options = []): array
    {
        // Accept field atts to filter the API fields
        $this->maybeFilterDataloadQueryArgs($query, $options);

        // To bring all results, limit is 0, not -1
        if (isset($query['limit']) && (int) $query['limit'] == -1) {
            $query['limit'] = 0;
        }
        $return_type = $options['return-type'] ?? null;
        if ($return_type == ReturnTypes::ARRAY || $return_type == ReturnTypes::IDS) {
            // Watch out: $query has the format needed by Events Manager for EM_Locations::get($query)
            $query['array'] = true;
        }
        if (isset($query['customPostID'])) {
            $query['post_id'] = $query['customPostID'];
            unset($query['customPostID']);
        } elseif (isset($query['include'])) {
            $query['post_id'] = implode(',', $query['include']);
            unset($query['include']);
        }

        // if (isset($query['status'])) {
        //     $query['status'] = $query['status'];
        //     unset($query['status']);
        // }

        // Tags
        if (isset($query['tag-ids'])) {
            $query['tag'] = implode(',', $query['tag-ids']);
            unset($query['tag-ids']);
        }
        if (isset($query['tags'])) {
            $query['tag'] = implode(',', $query['tags']);
            unset($query['tags']);
        }

        // Category
        if (isset($query['categories'])) {
            $query['category'] = implode(',', $query['categories']);
            unset($query['categories']);
        }

        // Profile
        $query['owner'] = false;
        if (isset($query['authors'])) {
            // Make sure it is an array of integers
            $query['owner'] = array_map('intval', $query['authors']);
            unset($query['authors']);
        }

        // Allow CoAuthors Plus to modify the query to add the coauthors
        $query = HooksAPIFacade::getInstance()->applyFilters(
            'EM_PoP_Events_API:get:query',
            $query
        );

        $results = EM_Events::get($query);

        return ($return_type == ReturnTypes::IDS) ?
            array_map(fn ($value) => $value['post_id'], $results) :
            $results;
    }

    public function getEventCount($query = array(), array $options = []): int
    {
        $options['return-type'] = ReturnTypes::IDS;

        // All results, no offset
        $query['limit'] = -1;
        unset($query['offset']);

        // Execute query and count results
        $events = $this->getEvents($query, $options);
        return count($events);
    }

    public function getCategories($EM_Event): array
    {
        // Returns an array of (term_id => category_object)
        return $EM_Event->get_categories()->terms;
    }

    public function isEvent($customPostObjectOrID): bool
    {
        if (is_numeric($customPostObjectOrID)) {
            $customPostID = $customPostObjectOrID;
        } else {
            $customPost = $customPostObjectOrID;
            $customPostID = $this->getID($customPost);
        }
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return $customPostTypeAPI->getCustomPostType($customPostID) == $this->getEventCustomPostType();
    }

    public function getLocation($EM_Event)
    {
        return $EM_Event->output('#_LOCATIONPOSTID');
    }

    public function getDates($EM_Event)
    {
        return $EM_Event->output('#_EVENTDATES');
    }

    public function getTimes($EM_Event)
    {
        return $EM_Event->output('#_EVENTTIMES');
    }

    public function getStartDate($EM_Event)
    {
        return $EM_Event->output('#_EVENTDATESTART');
    }

    public function getEndDate($EM_Event)
    {
        return $EM_Event->output('#_EVENTDATEEND');
    }

    public function getFormattedStartDate($EM_Event, $format)
    {
        return date_i18n($format, $EM_Event->start);
    }

    public function getFormattedEndDate($EM_Event, $format)
    {
        return date_i18n($format, $EM_Event->end);
    }

    public function isAllDay($EM_Event): bool
    {
        // This returns a string. Return a bool instead
        $value = $EM_Event->output('#_EVENTALLDAY');
        return $value ? true : false;
    }

    public function getGooglecalendarUrl($EM_Event)
    {
        return $EM_Event->output('#_EVENTGCALURL');
    }

    public function getIcalUrl($EM_Event)
    {
        return $EM_Event->output('#_EVENTICALURL');
    }

    public function getEventCustomPostType(): string
    {
        return \EM_POST_TYPE_EVENT;
    }

    public function getEventCustomPostTypeSlug(): string
    {
        return \EM_POST_TYPE_EVENT_SLUG;
    }

    public function getEventCategoryTaxonomy(): string
    {
        return \EM_TAXONOMY_CATEGORY;
    }
}
