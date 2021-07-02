<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Application_TaxonomyQuery_ConfigurationUtils
{
    public static function enableFilterAllcontentByTaxonomy()
    {
        return HooksAPIFacade::getInstance()->applyFilters('PoP_Application_ConfigurationUtils:enable-filter-allcontent-by-taxonomy', false);
    }
}
