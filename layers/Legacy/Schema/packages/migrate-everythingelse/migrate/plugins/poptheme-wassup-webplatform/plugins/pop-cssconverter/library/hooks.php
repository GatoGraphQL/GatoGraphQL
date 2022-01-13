<?php

class PoP_ThemeWassupWebPlatform_CSSConverter_Hooks
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

        // These are all the extra styles needed, only for the automated emails
        $files[] = POPTHEME_WASSUPWEBPLATFORM_DIR.'/css/dist/bundles/poptheme-wassup.bundle.min.css';
        return $files;
    }
}


/**
 * Initialization
 */
new PoP_ThemeWassupWebPlatform_CSSConverter_Hooks();
