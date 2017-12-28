<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_ServiceWorkers_CDN_Hooks {

    function __construct() {
        
        add_filter(
            'PoP_ServiceWorkers_Job_SW:configuration',
            array($this, 'get_sw_configuration')
        );
    }

	public function get_sw_configuration($configuration) {
        
        // Add the CDN configuration from pop-cdn
        // Fill default empty values, so that the corresponding $variables in sw.js get replaced
        if (POP_CDN_CONTENT_URI) {
            
            $configuration['${contentCDNDomain}'] = POP_CDN_CONTENT_URI;
            $configuration['${contentCDNParams}'] = array(
                GD_URLPARAM_CDNTHUMBPRINT,
                // POP_CDNCORE_URLPARAM_VERSION,
            );
        }

        return $configuration;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServiceWorkers_CDN_Hooks();
