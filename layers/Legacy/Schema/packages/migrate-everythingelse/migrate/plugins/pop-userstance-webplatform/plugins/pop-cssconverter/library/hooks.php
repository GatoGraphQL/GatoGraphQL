<?php
use PoP\Hooks\Facades\HooksAPIFacade;

class PoP_UserStance_CSSConverter_Hooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'PoP_CSSConverter_ConversionManager:css-files',
            array($this, 'addCssFiles')
        );
    }

    public function addCssFiles($files)
    {
        $files[] = POP_USERSTANCEWEBPLATFORM_DIR.'/css/dist/bundles/pop-userstance.bundle.min.css';
        return $files;
    }
}


/**
 * Initialization
 */
new PoP_UserStance_CSSConverter_Hooks();
