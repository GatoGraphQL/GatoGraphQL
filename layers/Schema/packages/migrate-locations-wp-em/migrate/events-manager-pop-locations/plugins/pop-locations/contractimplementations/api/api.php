<?php

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

    public function getLocationByPostId($post_id)
    {
        return em_get_location($post_id, 'post_id');
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
