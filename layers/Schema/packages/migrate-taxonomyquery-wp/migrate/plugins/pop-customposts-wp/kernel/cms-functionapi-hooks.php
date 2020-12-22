<?php
namespace PoPSchema\TaxonomyQuery\WP;
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter(
    'CMSAPI:customposts:query',
    array(FunctionAPIUtils::class, 'convertTaxonomyQuery')
);
