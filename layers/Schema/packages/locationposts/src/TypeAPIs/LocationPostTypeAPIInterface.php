<?php

declare(strict_types=1);

namespace PoPSchema\LocationPosts\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface LocationPostTypeAPIInterface
{
    /**
     * Indicates if the passed object is of type LocationPost
     *
     * @param [type] $object
     * @return boolean
     */
    public function isInstanceOfLocationPostType($object): bool;
    /**
     * Get the locationPost with provided ID or, if it doesn't exist, null
     *
     * @param [type] $id
     * @return void
     */
    public function getLocationPost($id);
    /**
     * Indicate if an locationPost with provided ID exists
     *
     * @param [type] $id
     * @return void
     */
    public function locationPostExists($id): bool;
    /**
     * Get the locationPost with provided ID or, if it doesn't exist, null
     *
     * @param [type] $id
     * @return void
     */
    public function getLocationPosts($query, array $options = []): array;
}
