<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GetPoP_ServiceWorkers_Hooks_Manifest {

    function __construct() {
        
        add_filter(
            'PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:color',
            array($this, 'color')
        );
        add_filter(
            'PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:imagename',
            array($this, 'imagename')
        );
    }

    function imagename($imagename) {

    	if (GetPoP_Utils::is_demo()) {

    		return 'launcher-demo-icon-';
    	}

        return $imagename;
    }

    function color($color) {

    	if (GetPoP_Utils::is_demo()) {

    		return '#fcf9eb';
    	}

        return '#babfc4';
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_ServiceWorkers_Hooks_Manifest();
