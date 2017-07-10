<?php

class PoP_Bootstrap_FE_Hooks {

    function __construct() {

        add_filter(
            'PoP_Frontend_ConversionManager:css-files',
            array($this, 'add_css_files')
        );
    }

    function add_css_files($files) {

        $files[] = POP_BOOTSTRAPPROCESSORS_DIR.'/css/includes/cdn/bootstrap.3.3.7.min.css';
    	return $files;
    }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Bootstrap_FE_Hooks();