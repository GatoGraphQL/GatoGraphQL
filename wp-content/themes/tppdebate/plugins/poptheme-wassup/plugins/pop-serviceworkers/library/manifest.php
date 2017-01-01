<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class TPPDebate_ServiceWorkers_Hooks_Manifest {

    function __construct() {
        
        add_filter(
            'PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:color',
            array($this, 'color')
        );
    }

    function color($color) {

        return '#edf9f7';
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new TPPDebate_ServiceWorkers_Hooks_Manifest();
