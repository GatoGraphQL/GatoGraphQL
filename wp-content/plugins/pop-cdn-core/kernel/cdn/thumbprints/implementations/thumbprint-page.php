<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_CDNCORE_THUMBPRINT_PAGE', 'page');

class PoP_CDNCore_Thumbprint_Page extends PoP_CDNCore_Thumbprint_PostBase {

    public function get_name() {
        
        return POP_CDNCORE_THUMBPRINT_PAGE;
    }

    public function get_query() {
        
        return array_merge(
            parent::get_query(),
            array(
                'post_type' => array('page'),
            )
        );
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_Thumbprint_Page();
