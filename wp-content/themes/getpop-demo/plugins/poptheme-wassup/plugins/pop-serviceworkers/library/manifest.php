<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GetPoPDemo_ServiceWorkers_Hooks_Manifest {

    function __construct() {
        
        add_filter(
            'PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:color',
            array($this, 'color')
        );
    }

    function color($color) {

    	return '#fcec8c';
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoPDemo_ServiceWorkers_Hooks_Manifest();
