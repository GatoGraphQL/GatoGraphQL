<?php
/**
 * Class to work with user capability
 *
 * @package    User-Role-Editor
 * @subpackage Admin
 * @author     Vladimir Garagulya <support@role-editor.com>
 * @copyright  Copyright (c) 2010 - 2016, Vladimir Garagulya
 **/

class URE_Capability {

    const SPACE_REPLACER = '_URE-SR_';
    const SLASH_REPLACER = '_URE-SLR_';
    const VERT_LINE_REPLACER = '_URE-VLR_';

    
    public static function escape($cap_id) {
        
        $search = array(' ', '/', '|');
        $replace = array(self::SPACE_REPLACER, self::SLASH_REPLACER, self::VERT_LINE_REPLACER);
        
        $cap_id_esc = str_replace($search, $replace, $cap_id);
                
        return $cap_id_esc;
    }
    // end escape()

    
    // sanitize user input for security
    public static function validate($cap_id_raw) {
        $match = array();
        $found = preg_match('/[A-Za-z0-9_\-]*/', $cap_id_raw, $match);
        if ( !$found || ($found && ($match[0]!=$cap_id_raw)) ) { // some non-alphanumeric charactes found!    
            $result = false;
        } else {
            $result = true;
        }
        $data = array('result'=>$result, 'cap_id'=>strtolower($match[0]));
        
        return $data;
    }
    // end of validate()
    
    
    /**
     * Add new user capability
     * 
     * @global WP_Roles $wp_roles
     * @return string
     */
    public static function add() {
        global $wp_roles;

        if (!current_user_can('ure_create_capabilities')) {
            return esc_html__('Insufficient permissions to work with User Role Editor','user-role-editor');
        }
        
        $mess = '';
        if (!isset($_POST['capability_id']) || empty($_POST['capability_id'])) {
            return 'Wrong Request';
        }
        
        $data = self::validate($_POST['capability_id']);                
        if (!$data['result']) {
            return esc_html__('Error: Capability name must contain latin characters and digits only!', 'user-role-editor');
        }
        
        $cap_id = $data['cap_id'];                
        $lib = URE_Lib::get_instance();
        $lib->get_user_roles();
        $lib->init_full_capabilities();
        $full_capabilities = $lib->get('full_capabilities');
        if (!isset($full_capabilities[$cap_id])) {
            $admin_role = $lib->get_admin_role();            
            $wp_roles->use_db = true;
            $wp_roles->add_cap($admin_role, $cap_id);
            $mess = sprintf(esc_html__('Capability %s is added successfully', 'user-role-editor'), $cap_id);
        } else {
            $mess = sprintf(esc_html__('Capability %s exists already', 'user-role-editor'), $cap_id);
        }
        
        return $mess;
    }
    // end of add()
    
    
    /**
     * Delete capability
     * 
     * @global wpdb $wpdb
     * @global WP_Roles $wp_roles
     * @return string - information message
     */
    public static function delete() {
        global $wpdb, $wp_roles;

        
        if (!current_user_can('ure_delete_capabilities')) {
            return esc_html__('Insufficient permissions to work with User Role Editor','user-role-editor');
        }
        
        if (!isset($_POST['user_capability_id']) || empty($_POST['user_capability_id'])) {
            return 'Wrong Request';
        }
        
        $lib = URE_Lib::get_instance();
        $mess = '';        
        $capability_id = $_POST['user_capability_id'];
        $caps_to_remove = $lib->get_caps_to_remove();
        if (!is_array($caps_to_remove) || count($caps_to_remove) == 0 || !isset($caps_to_remove[$capability_id])) {
            return sprintf(esc_html__('Error! You do not have permission to delete this capability: %s!', 'user-role-editor'), $capability_id);
        }

        // process users
        $usersId = $wpdb->get_col("SELECT $wpdb->users.ID FROM $wpdb->users");
        foreach ($usersId as $user_id) {
            $user = get_user_to_edit($user_id);
            if ($user->has_cap($capability_id)) {
                $user->remove_cap($capability_id);
            }
        }

        // process roles
        foreach ($wp_roles->role_objects as $wp_role) {
            if ($wp_role->has_cap($capability_id)) {
                $wp_role->remove_cap($capability_id);
            }
        }

        $mess = sprintf(esc_html__('Capability %s was removed successfully', 'user-role-editor'), $capability_id);        

        return $mess;
    }
    // end of delete()
    
}
// end of class URE_Capability
