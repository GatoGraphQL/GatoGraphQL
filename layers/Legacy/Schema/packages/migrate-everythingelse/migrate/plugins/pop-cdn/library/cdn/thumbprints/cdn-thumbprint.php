<?php

class PoP_CDN_ThumbprintBase
{
    public function __construct()
    {
        global $pop_cdn_thumbprint_manager;
        $pop_cdn_thumbprint_manager->add($this);
    }

    public function getName(): string
    {
        return '';
    }
    
    public function getQuery()
    {
        return array();
    }

    /**
     * @return mixed[]
     */
    public function executeQuery(array $query, array $options = []): array
    {
        return array();
    }
    
    public function getTimestamp($object_id)
    {
        return (int) 0;
    }
}
