<?php

class PoP_SectionProcessors_FE_Hooks {

    function __construct() {

        add_filter(
            'PoP_Frontend_ConversionManager:css-files',
            array($this, 'add_css_files')
        );
    }

    function add_css_files($files) {

        $files[] = POPTHEME_WASSUP_SECTIONPROCESSORS_DIR.'/css/dist/poptheme-wassup-sectionprocessors.bundle.min.css';
    	return $files;
    }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_SectionProcessors_FE_Hooks();