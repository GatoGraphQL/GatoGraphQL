<?php

class PoP_MediaHostThumbs_Utils
{
    public static function getHostThumbIds()
    {

        // List of $host => $thumb_id
        // Eg: 'guardian.com' => 53433
        return \PoP\Root\App::applyFilters('gdThumbDefault:host_thumb_ids', array());
    }

    public static function getNonembeddableHosts()
    {
        return \PoP\Root\App::applyFilters('PoP_MediaHostThumbs_Utils:nonembeddable-hosts', array());
    }
}
