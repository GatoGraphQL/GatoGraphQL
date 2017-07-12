<?php

class PoP_ThemeWassup_FE_Hooks {

    function __construct() {

        add_filter(
            'PoP_Frontend_ConversionManager:css-files',
            array($this, 'add_css_files')
        );
    }

    function add_css_files($files) {

        // These are all the extra styles needed, only for the automated emails
        $files[] = POPTHEME_WASSUP_DIR.'/css/dist/poptheme-wassup.bundle.min.css';
        $files[] = POPTHEME_WASSUP_DIR.'/plugins/pop-frontendengine/css/poptheme-wassup-automatedemails.css';
    	return $files;
    }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ThemeWassup_FE_Hooks();