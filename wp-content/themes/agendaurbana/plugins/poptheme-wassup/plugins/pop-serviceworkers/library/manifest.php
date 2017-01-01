<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class AgendaUrbana_ServiceWorkers_Hooks_Manifest {

    function __construct() {
        
        add_filter(
            'PoPTheme_Wassup_ServiceWorkers_Hooks_Manifest:color',
            array($this, 'color')
        );
    }

    function color($color) {

        return '#eff8fb';
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new AgendaUrbana_ServiceWorkers_Hooks_Manifest();
