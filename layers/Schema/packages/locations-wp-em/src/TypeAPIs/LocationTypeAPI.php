<?php

declare(strict_types=1);

namespace PoPSchema\LocationsWPEM\TypeAPIs;

use WP_Post;
use PoPSchema\Locations\TypeAPIs\LocationTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class LocationTypeAPI implements LocationTypeAPIInterface
{
    /**
     * Return the location's ID
     *
     * @param [type] $location
     * @return void
     */
    public function getID($location)
    {
        return $location->post_id;
    }
    /**
     * Indicates if the passed object is of type Location
     */
    public function isInstanceOfLocationType(object $object): bool
    {
        return ($object instanceof WP_Post) && $object->post_type == \EM_POST_TYPE_LOCATION;
    }
}
