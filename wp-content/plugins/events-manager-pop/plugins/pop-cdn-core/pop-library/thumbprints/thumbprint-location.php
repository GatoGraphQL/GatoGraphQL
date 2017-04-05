<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_EM_CDN_THUMBPRINT_LOCATION', 'location');

class PoP_EM_CDN_Thumbprint_Location extends PoP_CDNCore_Thumbprint_PostBase {

    public function get_name() {
        
        return POP_EM_CDN_THUMBPRINT_LOCATION;
    }

    public function get_query() {
        
        return array_merge(
            parent::get_query(),
            array(
                'post_type' => array(EM_POST_TYPE_LOCATION),
            )
        );
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_EM_CDN_Thumbprint_Location();
