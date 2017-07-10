<?php

class GetPoPDemo_FE_Hooks {

    function __construct() {

        add_filter(
            'PoP_Frontend_ConversionManager:css-files',
            array($this, 'add_css_files')
        );
    }

    function add_css_files($files) {

        $files[] = GETPOPDEMO_DIR.'/css/dist/getpop-demo.bundle.min.css';
    	return $files;
    }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoPDemo_FE_Hooks();