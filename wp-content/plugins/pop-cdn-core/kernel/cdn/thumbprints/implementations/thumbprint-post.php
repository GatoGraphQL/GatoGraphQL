<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_CDNCORE_THUMBPRINT_POST', 'post');

class PoP_CDNCore_Thumbprint_Post extends PoP_CDNCore_Thumbprint_PostBase {

    public function get_name() {
        
        return POP_CDNCORE_THUMBPRINT_POST;
    }

    public function get_query() {
        
        return array_merge(
            parent::get_query(),
            array(
                'post_type' => gd_dataload_posttypes(),
            )
        );
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_Thumbprint_Post();
