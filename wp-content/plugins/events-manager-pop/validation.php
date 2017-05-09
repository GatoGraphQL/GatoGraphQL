<?php

define('POP_ENGINE_MIN_VERSION', 0.1);
define('EMPOP_EM_MIN_VERSION', 5.61);

class EM_PoP_Validation {

	function validate(){
		
		$success = true;

		// Validate PoP
		if(!defined('POP_ENGINE_VERSION')){

			add_action('admin_notices',array($this,'pop_install_warning'));
			add_action('network_admin_notices',array($this,'pop_install_warning'));
			$success = false;
		}
		elseif(!defined('POP_ENGINE_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(POP_ENGINE_MIN_VERSION > POP_ENGINE_VERSION){
			
			add_action('admin_notices',array($this,'pop_version_warning'));
			add_action('network_admin_notices',array($this,'pop_version_warning'));
		}
		// Validate plug-in
		if(!defined('EM_VERSION')){

			add_action('admin_notices',array($this,'plugin_install_warning'));
			add_action('network_admin_notices',array($this,'plugin_install_warning'));
			$success = false;
		}
		elseif(EMPOP_EM_MIN_VERSION > EM_VERSION){
			
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
	function pop_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP—Platform of Platforms</b> is not installed/activated. Without it, <b>Events Manager for PoP</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function pop_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>PoP—Platform of Platforms</b> installed, or otherwise <b>Events Manager for PoP</b> might not function properly.','ps-pop'));
	}
	function plugin_install_warning(){
		
		$this->admin_notice(__('Error: <b>Events Manager</b> is not installed/activated. Without it, <b>Events Manager for PoP</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function plugin_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>Events Manager</b> installed, or otherwise <b>Events Manager for PoP</b> might not function properly.','ps-pop'));
	}
}
