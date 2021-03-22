<?php

declare(strict_types=1);

namespace PoPSchema\Events\TypeAPIs;

use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface EventTypeAPIInterface extends CustomPostTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type Event
     */
    public function isInstanceOfEventType(object $object): bool;
    /**
     * Indicate if an event with provided ID exists
     */
    public function eventExists(mixed $id): bool;
    /**
     * Get the event with provided ID or, if it doesn't exist, null
     */
    public function getEvent(mixed $customPostID): ?object;
    /**
     * Get the list of events
     *
     * @param array $query
     */
    public function getEvents($query = array(), array $options = []): array;
    /**
     * Get the number of events
     *
     * @param array $query
     * @return array
     */
    public function getEventCount($query = array(), array $options = []): int;

    public function isFutureEvent($post_or_post_id): bool;
    public function isCurrentEvent($post_or_post_id): bool;
    public function isPastEvent($post_or_post_id): bool;
    public function getCategories($event): array;
    public function getLocation($event);
    public function getDates($event);
    public function getTimes($event);
    public function getStartDate($event);
    public function getEndDate($event);
    public function getFormattedStartDate($event, $format);
    public function getFormattedEndDate($event, $format);
    public function isAllDay($event): bool;
    public function getGooglecalendarUrl($event);
    public function getIcalUrl($event);

    public function isEvent($customPostObjectOrID): bool;
    /**
     * Function needed for the Delegator TypeResolver (CustomPostUnionTypeResolver::class)
     * to decide what typeResolver to use based on the object's post type
     */
    public function getEventCustomPostType(): string;
    public function getEventCustomPostTypeSlug(): string;
    public function getEventCategoryTaxonomy(): string;
}
