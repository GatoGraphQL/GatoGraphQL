<?php

declare(strict_types=1);

namespace PoPSchema\LocationsWPEM\TypeAPIs;

use WP_Post;
use EM_Locations;
use PoPSchema\Locations\TypeAPIs\LocationTypeAPIInterface;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class LocationTypeAPI implements LocationTypeAPIInterface
{
    /**
     * Return the location's ID
     */
    public function getID(object $location): string | int
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

    public function getLocation(string | int $location_id): object
    {
        return em_get_location($location_id);
    }

    public function getLocations($args = array(), array $options = []): array
    {
        // To bring all results, limit is 0, not -1
        if (isset($args['limit']) && $args['limit'] == -1) {
            $args['limit'] = 0;
        }
        $return_type = $options['return-type'] ?? null;
        if ($return_type == ReturnTypes::ARRAY || $return_type == ReturnTypes::IDS) {
            // Watch out: $query has the format needed by Events Manager for EM_Locations::get($args)
            $args['array'] = true;
        }
        if (isset($args['customPostID'])) {
            $args['post_id'] = $args['customPostID'];
            unset($args['customPostID']);
        } elseif (isset($args['include'])) {
            $args['post_id'] = implode(',', $args['include']);
            unset($args['include']);
        }

        $results = EM_Locations::get($args);

        if ($return_type == ReturnTypes::IDS) {
            return array_map(
                function ($value) {
                    return $value['post_id'];
                },
                $results
            );
        }
        return $results;
    }

    public function getLatitude(object $EM_Location): string
    {
        return $EM_Location->location_latitude;
    }

    public function getLongitude(object $EM_Location): string
    {
        return $EM_Location->location_longitude;
    }

    public function getName(object $EM_Location): string
    {
        return $EM_Location->output('#_LOCATIONNAME');
    }

    public function getAddress(object $EM_Location): string
    {
        return $EM_Location->output('#_LOCATIONFULLLINE');
    }

    public function getCity(object $EM_Location): string
    {
        return $EM_Location->output('#_LOCATIONTOWN');
    }
}
