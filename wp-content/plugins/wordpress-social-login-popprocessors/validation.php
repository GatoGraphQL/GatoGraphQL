<?php

define('WSLPOP_WSL_MIN_VERSION', "2.3.0");
define('WSLPOPPROCESSORS_POP_COREPROCESSORS_MIN_VERSION', 0.1);
define('WSLPOPPROCESSORS_WSL_POP_MIN_VERSION', 0.1);

class WSL_PoPProcessors_Validation {

	function validate(){
		
		$success = true;
		// global $WORDPRESS_SOCIAL_LOGIN_VERSION;

		
		// Validate PoP Core Processors
		if(!defined('POP_COREPROCESSORS_VERSION')){

			add_action('admin_notices',array($this,'core_install_warning'));
			add_action('network_admin_notices',array($this,'core_install_warning'));
			$success = false;
		}
		elseif(!defined('POP_COREPROCESSORS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(WSLPOPPROCESSORS_POP_COREPROCESSORS_MIN_VERSION > POP_COREPROCESSORS_VERSION){
			
			add_action('admin_notices',array($this,'core_version_warning'));
			add_action('network_admin_notices',array($this,'core_version_warning'));
		}

		// Validate WSL PoP
		if(!defined('WSL_POP_VERSION')){

			add_action('admin_notices',array($this,'wslpop_install_warning'));
			add_action('network_admin_notices',array($this,'wslpop_install_warning'));
			$success = false;
		}
		elseif(!defined('WSL_POP_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(WSLPOPPROCESSORS_WSL_POP_MIN_VERSION > WSL_POP_VERSION){
			
			add_action('admin_notices',array($this,'wslpop_version_warning'));
			add_action('network_admin_notices',array($this,'wslpop_version_warning'));
		}

		// // Validate plug-in
		// if (!function_exists('wsl_activate')) {
		// // if (!$WORDPRESS_SOCIAL_LOGIN_VERSION) {

		// 	add_action('admin_notices',array($this,'plugin_install_warning'));
		// 	add_action('network_admin_notices',array($this,'plugin_install_warning'));
		// 	$success = false;
		// }
		// elseif(WSLPOP_WSL_MIN_VERSION > $WORDPRESS_SOCIAL_LOGIN_VERSION){
			
		// 	add_action('admin_notices',array($this,'plugin_version_warning'));
		// 	add_action('network_admin_notices',array($this,'plugin_version_warning'));
		// }

		return $success;	
	}
	function admin_notice($message){
		?>
		<div class="error"><p><?php echo $message ?><br/><em><?php _e('Only admins see this message.','ps-pop'); ?></em></p></div>
		<?php
	}
	function core_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Core Processors</b> is not installed/activated. Without it, <b>Wordpress Social Login PoP Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function core_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>PoP Core Processors</b> installed, or otherwise <b>Wordpress Social Login PoP Processors</b> might not function properly.','ps-pop'));
	}
	function wslpop_install_warning(){
		
		$this->admin_notice(__('Error: <b>Wordpress Social Login for PoP</b> is not installed/activated. Without it, <b>Wordpress Social Login PoP Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function wslpop_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>Wordpress Social Login for PoP</b> installed, or otherwise <b>Wordpress Social Login PoP Processors</b> might not function properly.','ps-pop'));
	}
	// function plugin_install_warning(){
		
	// 	$this->admin_notice(__('Error: <b>Wordpress Social Login</b> is not installed/activated. Without it, <b>Wordpress Social Login for PoP Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	// }
	// function plugin_version_warning(){
		
	// 	$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>Wordpress Social Login</b> installed, or otherwise <b>Wordpress Social Login for PoP Processors</b> might not function properly.','ps-pop'));
	// }
}
