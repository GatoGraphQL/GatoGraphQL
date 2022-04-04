<?php
namespace PoPCMSSchema\TaxonomyQuery;

class PoP_Application_TaxonomyQuery_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::addFilter(
            'useAllcontentCategories',
            $this->useAllcontentCategories(...)
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
