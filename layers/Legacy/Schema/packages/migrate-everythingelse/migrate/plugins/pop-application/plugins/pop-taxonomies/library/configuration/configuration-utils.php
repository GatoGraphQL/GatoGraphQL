<?php

class PoP_Application_Taxonomy_ConfigurationUtils
{
    public static function hookAllcontentComponents()
    {
        return \PoP\Root\App::getHookManager()->applyFilters('PoP_Application_ConfigurationUtils:hook-allcontent-components', false);
    }
}
