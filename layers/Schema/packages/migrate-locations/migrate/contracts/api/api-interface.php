<?php

interface PoP_Locations_API
{
    public function getNewLocationObject();
    public function getPost($location, $validate = true);
    public function save($location);
    public function getErrors($location);
    public function getLocationByPostId($post_id);

    // This function is not ideal, since it ties the interface to WordPress logic,
    public function getLocationPostType();
    public function getLocationPostTypeSlug();
}
