<?php
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_MediaHostThumbs_Utils
{
    public static function getHostThumbIds()
    {

        // List of $host => $thumb_id
        // Eg: 'guardian.com' => 53433
        return HooksAPIFacade::getInstance()->applyFilters('gdThumbDefault:host_thumb_ids', array());
    }

    public static function getNonembeddableHosts()
    {
        return HooksAPIFacade::getInstance()->applyFilters('PoP_MediaHostThumbs_Utils:nonembeddable-hosts', array());
    }
}
