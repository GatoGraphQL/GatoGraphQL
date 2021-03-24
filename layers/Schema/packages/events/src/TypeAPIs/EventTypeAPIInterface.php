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
    public function eventExists(int | string $id): bool;
    /**
     * Get the event with provided ID or, if it doesn't exist, null
     */
    public function getEvent(int | string $customPostID): ?object;
    /**
     * Get the list of events
     *
     * @param array $query
     */
    public function getEvents($query = array(), array $options = []): array;
    /**
     * Get the number of events
     */
    public function getEventCount($query = array(), array $options = []): int;

    public function isFutureEvent(string | int | object $post_or_post_id): bool;
    public function isCurrentEvent(string | int | object $post_or_post_id): bool;
    public function isPastEvent(string | int | object $post_or_post_id): bool;
    public function getCategories(object $event): array;
    public function getLocation(object $event);
    public function getDates(object $event);
    public function getTimes(object $event);
    public function getStartDate(object $event);
    public function getEndDate(object $event);
    public function getFormattedStartDate(object $event, string $format);
    public function getFormattedEndDate(object $event, string $format);
    public function isAllDay(object $event): bool;
    public function getGooglecalendarUrl(object $event);
    public function getIcalUrl(object $event);

    public function isEvent($customPostObjectOrID): bool;
    /**
     * Function needed for the Delegator TypeResolver (CustomPostUnionTypeResolver::class)
     * to decide what typeResolver to use based on the object's post type
     */
    public function getEventCustomPostType(): string;
    public function getEventCustomPostTypeSlug(): string;
    public function getEventCategoryTaxonomy(): string;
}
