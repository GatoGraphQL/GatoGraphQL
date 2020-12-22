<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

class PoP_Application_RequestMeta_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            '\PoP\ComponentModel\Engine:request-meta',
            array($this, 'getRequestMeta')
        );
        HooksAPIFacade::getInstance()->addFilter(
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
        $vars = ApplicationState::getVars();
        $meta['version'] = $vars['version'];
        return $meta;
    }
}

/**
 * Initialization
 */
new PoP_Application_RequestMeta_Hooks();
