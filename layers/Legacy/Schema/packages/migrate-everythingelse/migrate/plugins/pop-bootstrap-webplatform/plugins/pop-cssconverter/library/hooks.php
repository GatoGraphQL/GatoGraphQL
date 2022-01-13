<?php

class PoP_Bootstrap_CSSConverter_Hooks
{
    public function __construct()
    {
        \PoP\Root\App::getHookManager()->addFilter(
            'PoP_CSSConverter_ConversionManager:css-files',
            array($this, 'addCssFiles')
        );
    }

    public function addCssFiles($files)
    {
        $files[] = POP_BOOTSTRAPWEBPLATFORM_DIR.'/css/includes/cdn/bootstrap.3.3.7.min.css';
        return $files;
    }
}


/**
 * Initialization
 */
new PoP_Bootstrap_CSSConverter_Hooks();
