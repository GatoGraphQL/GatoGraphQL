<?php

define('WSLPOP_WSL_MIN_VERSION', "2.3.0");

class WSL_PoP_Validation {

	function validate(){
		
		$success = true;
		global $WORDPRESS_SOCIAL_LOGIN_VERSION;

		
		// Validate plug-in
		if (!function_exists('wsl_activate')) {
		// if (!$WORDPRESS_SOCIAL_LOGIN_VERSION) {

			add_action('admin_notices',array($this,'plugin_install_warning'));
			add_action('network_admin_notices',array($this,'plugin_install_warning'));
			$success = false;
		}
		elseif(WSLPOP_WSL_MIN_VERSION > $WORDPRESS_SOCIAL_LOGIN_VERSION){
			
			add_action('admin_notices',array($this,'plugin_version_warning'));
			add_action('network_admin_notices',array($this,'plugin_version_warning'));
		}

		return $success;	
	}
	function admin_notice($message){
		?>
		<div class="error"><p><?php echo $message ?><br/><em><?php _e('Only admins see this message.','ps-pop'); ?></em></p></div>
		<?php
	}
	function plugin_install_warning(){
		
		$this->admin_notice(__('Error: <b>Wordpress Social Login</b> is not installed/activated. Without it, <b>Wordpress Social Login for PoP</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function plugin_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>Wordpress Social Login</b> installed, or otherwise <b>Wordpress Social Login for PoP</b> might not function properly.','ps-pop'));
	}
}
