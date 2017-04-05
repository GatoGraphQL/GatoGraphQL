<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_CDNCore_Thumbprint_PostBase extends PoP_CDNCore_ThumbprintBase {

    public function get_query() {
        
        return array(
            'fields' => 'ids',
            'limit' => 1,
            'orderby' => 'modified',
            'order' => 'DESC',
            'post_status' => 'publish',
        );
    }

    public function execute_query($query) {
        
        return get_posts($query);
    }
    
    public function get_timestamp($post_id) {

        // Comment Leo 02/04/2017: can't use functions get_the_modified_time or get_post_modified_time because their hooks
        // do not include the $post, then qTranslateX is hooking into there but uses global $post, which breaks everything
        // return get_the_modified_time('U', $post_id);

        // Doing it the manual way
        $post = get_post($post_id);
        return mysql2date('U', $post->post_modified);
    }
}
