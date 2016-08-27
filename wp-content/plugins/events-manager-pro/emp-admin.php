<?php
/**
 * Used for hooking into the admin page and adding extra settings specific to Events Manager Pro, meaning classes do not need to be loaded until enabled here. 
 */
class EM_Pro_Admin{
    public static function init(){
        //Logging
        add_action('em_options_page_panel_admin_tools', 'EM_Pro_Admin::logging_settings');
        add_action('add_option_dbem_enable_logging', 'EM_Pro_Admin::logging_enable', 10, 2);
        add_action('update_option_dbem_enable_logging', 'EM_Pro_Admin::logging_enable', 10, 2);
        //MB Mode
        include('add-ons/multiple-bookings/multiple-bookings-admin.php');
    }
    
    /* START Logging */
	public static function logging_settings(){
	    ?>
	    <h4 style="font-size:1.1em;"><?php _e ( 'Logging', 'dbem' ); ?></h4>
		<table class="form-table">
			<?php em_options_radio_binary ( __( 'Enable Logging?', 'em-pro' ), 'dbem_enable_logging', sprintf(__('If enabled, a folder called %s will be created. Please ensure that your wp-contents folder is writable by the server.','em-pro'), '<code>'.WP_PLUGIN_DIR.'/events-manager-logs'.'</code>')); ?>
		</table>
		<?php
	}
	
	public static function logging_enable($old_val, $new_val){
		global $EM_Notices;
		if( $new_val && $new_val != $old_val ){
			if( !EM_Pro::log('Logging Enabled','general', true) ){
				$EM_Notices->add_error(__('Could not create a log directory, please make sure your wp-content is writeable.'.'em-pro'));
			}
		}
	}
	/* END Logging */
}
EM_Pro_Admin::init();