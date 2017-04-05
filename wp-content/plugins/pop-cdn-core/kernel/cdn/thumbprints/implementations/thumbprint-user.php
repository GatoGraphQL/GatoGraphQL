<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_CDNCORE_THUMBPRINT_USER', 'user');

class PoP_CDNCore_Thumbprint_User extends PoP_CDNCore_ThumbprintBase {

    public function get_name() {
        
        return POP_CDNCORE_THUMBPRINT_USER;
    }

    public function get_query() {
        
        return array(
            'fields' => 'ID', 
            'limit' => 1,
            'orderby' => 'meta_value',
            'meta_key' => GD_MetaManager::get_meta_key(GD_METAKEY_PROFILE_LASTEDITED, GD_META_TYPE_USER),
            'order' => 'DESC',
            'role' => GD_ROLE_PROFILE,
        );
    }

    public function execute_query($query) {
        
        return get_users($query);
    }
    
    public function get_timestamp($user_id) {

        return GD_MetaManager::get_user_meta($user_id, GD_METAKEY_PROFILE_LASTEDITED, true);
    }
}
    
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_Thumbprint_User();
