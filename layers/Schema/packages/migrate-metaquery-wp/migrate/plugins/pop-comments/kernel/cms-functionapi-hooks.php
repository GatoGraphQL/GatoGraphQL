<?php
namespace PoPSchema\MetaQuery\WP;
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter(
    'CMSAPI:comments:query',
    array(FunctionAPIUtils::class, 'convertMetaQuery')
);