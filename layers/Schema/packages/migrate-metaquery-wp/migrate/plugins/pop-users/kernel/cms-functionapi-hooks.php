<?php
namespace PoPSchema\MetaQuery\WP;
use PoP\Hooks\Facades\HooksAPIFacade;

HooksAPIFacade::getInstance()->addFilter(
    'CMSAPI:users:query',
    array(FunctionAPIUtils::class, 'convertMetaQuery')
);