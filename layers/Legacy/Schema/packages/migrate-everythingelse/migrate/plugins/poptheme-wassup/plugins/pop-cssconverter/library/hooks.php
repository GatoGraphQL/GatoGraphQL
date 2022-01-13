<?php

class PoPTheme_Wassup_CSSConverter_Hooks
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
        $files[] = POPTHEME_WASSUP_DIR.'/plugins/pop-cssconverter/css/poptheme-wassup-automatedemails.css';
        return $files;
    }
}


/**
 * Initialization
 */
new PoPTheme_Wassup_CSSConverter_Hooks();
