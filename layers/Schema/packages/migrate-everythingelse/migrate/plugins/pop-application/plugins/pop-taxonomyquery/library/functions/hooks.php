<?php
namespace PoPSchema\TaxonomyQuery;
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_Application_TaxonomyQuery_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'useAllcontentCategories',
            array($this, 'useAllcontentCategories')
        );
    }

    public function useAllcontentCategories($use)
    {
        return \PoP_Application_TaxonomyQuery_ConfigurationUtils::enableFilterAllcontentByTaxonomy();
    }
}

/**
 * Initialization
 */
new PoP_Application_TaxonomyQuery_Hooks();
