<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Application_Taxonomy_ConfigurationUtils
{
    public static function hookAllcontentComponents()
    {
        return HooksAPIFacade::getInstance()->applyFilters('PoP_Application_ConfigurationUtils:hook-allcontent-components', false);
    }
}
