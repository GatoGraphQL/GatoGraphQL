<?php

use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoP_Application_RequestMeta_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            '\PoP\ComponentModel\Engine:request-meta',
            array($this, 'getRequestMeta')
        );
        \PoP\Root\App::getHookManager()->addFilter(
            '\PoP\ComponentModel\Engine:site-meta',
            array($this, 'getSiteMeta')
        );
    }

    public function getRequestMeta($meta)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $meta[GD_URLPARAM_TITLE] = $cmsapplicationapi->getDocumentTitle();
        return $meta;
    }

    public function getSiteMeta($meta)
    {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $meta['sitename'] = $cmsapplicationapi->getSiteName();
        $meta['version'] = ApplicationInfoFacade::getInstance()->getVersion();
        return $meta;
    }
}

/**
 * Initialization
 */
new PoP_Application_RequestMeta_Hooks();
