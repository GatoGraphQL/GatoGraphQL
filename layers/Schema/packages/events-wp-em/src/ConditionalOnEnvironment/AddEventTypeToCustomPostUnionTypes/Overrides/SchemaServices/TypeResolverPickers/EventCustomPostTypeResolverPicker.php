<?php

declare(strict_types=1);

namespace PoPSchema\EventsWPEM\ConditionalOnEnvironment\AddEventTypeToCustomPostUnionTypes\Overrides\SchemaServices\TypeResolverPickers;

use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Events\Facades\EventTypeAPIFacade;
use PoPSchema\CustomPostsWP\TypeResolverPickers\CustomPostTypeResolverPickerInterface;
use PoPSchema\Events\ConditionalOnEnvironment\AddEventTypeToCustomPostUnionTypes\TypeResolverPickers\EventCustomPostTypeResolverPicker as UpstreamEventCustomPostTypeResolverPicker;

class EventCustomPostTypeResolverPicker extends UpstreamEventCustomPostTypeResolverPicker implements CustomPostTypeResolverPickerInterface
{
    /**
     * Transform from WP_Post to EM_Event classes
     * Needed as to be able to access all fields from an event
     *
     * @param array $customPosts An array with "key" the ID, "value" the object
     * @return array
     */
    public function maybeCastCustomPosts(array $customPosts): array
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        $customPostIDs = array_keys($customPosts);
        $query = [
            'include' => $customPostIDs,
        ];
        $events = $eventTypeAPI->getEvents($query);
        // The response must be ordered by ID
        $eventsIDObjects = [];
        foreach ($events as $event) {
            $eventsIDObjects[$customPostTypeAPI->getID($event)] = $event;
        }
        return $eventsIDObjects;
    }

    public function getCustomPostType(): string
    {
        $eventTypeAPI = EventTypeAPIFacade::getInstance();
        return $eventTypeAPI->getEventCustomPostType();
    }
}
