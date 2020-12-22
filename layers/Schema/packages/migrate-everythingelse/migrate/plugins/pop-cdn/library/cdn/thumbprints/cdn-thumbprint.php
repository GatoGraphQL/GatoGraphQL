<?php

class PoP_CDN_ThumbprintBase
{
    public function __construct()
    {
        global $pop_cdn_thumbprint_manager;
        $pop_cdn_thumbprint_manager->add($this);
    }

    public function getName()
    {
        return '';
    }
    
    public function getQuery()
    {
        return array();
    }

    public function executeQuery($query, array $options = [])
    {
        return '';
    }
    
    public function getTimestamp($object_id)
    {
        return (int) 0;
    }
}
