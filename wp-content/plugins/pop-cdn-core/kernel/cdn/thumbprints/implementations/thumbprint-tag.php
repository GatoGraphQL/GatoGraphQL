<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_CDNCORE_THUMBPRINT_TAG', 'tag');

class PoP_CDNCore_Thumbprint_Tag extends PoP_CDNCore_ThumbprintBase {

    public function get_name() {
        
        return POP_CDNCORE_THUMBPRINT_TAG;
    }

    public function get_query() {
        
        return array(
            'fields' => 'ids',
            'limit' => 1,
            'orderby' => 'term_id',
            'order' => 'DESC',
        );
    }

    public function execute_query($query) {
        
        return get_tags($query);
    }
    
    public function get_timestamp($tag_id) {

        // Because tags never change, and the only activity for them is to have a new tag created,
        // then returning the tag_id of the last created tag is already enough
        return $tag_id;
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_Thumbprint_Tag();
