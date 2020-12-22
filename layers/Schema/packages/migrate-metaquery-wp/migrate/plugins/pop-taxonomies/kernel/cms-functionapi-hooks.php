<?php
namespace PoPSchema\MetaQuery\WP;
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter(
    'CMSAPI:tags:query',
    array(FunctionAPIUtils::class, 'convertMetaQuery')
);