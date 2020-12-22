<?php

interface PoP_Locations_API
{
    public function getNewLocationObject();
    public function getPost($location, $validate = true);
    public function save($location);
    public function getErrors($location);
    public function getLocation($location_id/* = false, $search_by = 'location_id'*/);
    public function getLocationByPostId($post_id);
    public function getLocations($args = array(), array $options = []);
    public function getLatitude($location);
    public function getLongitude($location);
    public function getName($location);
    public function getAddress($location);
    public function getCity($location);

    // This function is not ideal, since it ties the interface to WordPress logic,
    public function getLocationPostType();
    public function getLocationPostTypeSlug();
}
