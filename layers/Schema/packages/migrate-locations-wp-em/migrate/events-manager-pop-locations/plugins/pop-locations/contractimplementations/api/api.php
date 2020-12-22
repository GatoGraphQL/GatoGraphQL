<?php
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class EM_PoP_Locations_API extends PoP_Locations_API_Base implements PoP_Locations_API
{
    public function getNewLocationObject()
    {
        return new EM_Location();
    }

    public function getPost($EM_Location, $validate = true)
    {
        return $EM_Location->get_post($validate);
    }

    public function save($EM_Location)
    {
        return $EM_Location->save();
    }

    public function getErrors($EM_Location)
    {
        return $EM_Location->get_errors();
    }

    public function getLocation($location_id/* = false, $search_by = 'location_id'*/)
    {
        return em_get_location($location_id);
    }

    public function getLocationByPostId($post_id)
    {
        return em_get_location($post_id, 'post_id');
    }

    public function getLocations($args = array(), array $options = [])
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
        }
        elseif (isset($args['include'])) {

            $args['post_id'] = implode(',', $args['include']);
            unset($args['include']);
        }

        $results = EM_Locations::get($args);

        return ($return_type == ReturnTypes::IDS) ? array_map(function($value) {
            return $value['post_id'];
        }, $results) : $results;
    }

    public function getLatitude($EM_Location)
    {
        return $EM_Location->location_latitude;
    }
    public function getLongitude($EM_Location)
    {
        return $EM_Location->location_longitude;
    }
    public function getName($EM_Location)
    {
        return $EM_Location->output('#_LOCATIONNAME');
    }
    public function getAddress($EM_Location)
    {
        return $EM_Location->output('#_LOCATIONFULLLINE');
    }
    public function getCity($EM_Location)
    {
        return $EM_Location->output('#_LOCATIONTOWN');
    }

    public function getLocationPostType()
    {
        return EM_POST_TYPE_LOCATION;
    }

    public function getLocationPostTypeSlug()
    {
        return EM_POST_TYPE_LOCATION_SLUG;
    }
}

/**
 * Initialize
 */
new EM_PoP_Locations_API();
