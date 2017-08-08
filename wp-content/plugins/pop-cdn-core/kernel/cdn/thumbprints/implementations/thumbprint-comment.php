<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_CDNCORE_THUMBPRINT_COMMENT', 'comment');

class PoP_CDNCore_Thumbprint_Comment extends PoP_CDNCore_ThumbprintBase {

    public function get_name() {
        
        return POP_CDNCORE_THUMBPRINT_COMMENT;
    }

    public function get_query() {
        
        return array(
            'fields' => 'ids',
            'number' => 1,
            'status' => 'approve',
            'type' => 'comment', // Only comments, no trackbacks or pingbacks
            'order' =>  'DESC',
            'orderby' => 'comment_date_gmt',
        );
    }

    public function execute_query($query) {
        
        return get_comments($query);
    }
    
    public function get_timestamp($comment_id) {

        return get_comment_date('U', $comment_id);
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_Thumbprint_Comment();
