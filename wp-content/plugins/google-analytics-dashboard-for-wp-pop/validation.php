<?php

define('GADWPPOP_POP_COREPROCESSORS_MIN_VERSION', 0.1);

class GADWP_PoP_Validation {

	function validate() {
		
		$success = true;
		
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
		elseif(GADWPPOP_POP_COREPROCESSORS_MIN_VERSION > POP_COREPROCESSORS_VERSION){
			
			add_action('admin_notices',array($this,'core_version_warning'));
			add_action('network_admin_notices',array($this,'core_version_warning'));
		}

		// Validate plug-in
		if (!class_exists('GADWP_Manager')) {

			add_action('admin_notices',array($this,'plugin_install_warning'));
			add_action('network_admin_notices',array($this,'plugin_install_warning'));
			$success = false;
		}

		return $success;	
	}
	function admin_notice($message){
		?>
		<div class="error"><p><?php echo $message ?><br/><em><?php _e('Only admins see this message.','ps-pop'); ?></em></p></div>
		<?php
	}
	function core_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Core Processors</b> is not installed/activated. Without it, <b>Public Post Preview PoP Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function core_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>PoP Core Processors</b> installed, or otherwise <b>Public Post Preview PoP Processors</b> might not function properly.','ps-pop'));
	}
	function plugin_install_warning(){
		
		$this->admin_notice(__('Error: <b>Public Post Preview for PoP</b> is not installed/activated. Without it, <b>Google Analytics Dashboard for WP</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	// function plugin_version_warning(){
		
	// 	$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>Public Post Preview for PoP</b> installed, or otherwise <b>Public Post Preview PoP Processors</b> might not function properly.','ps-pop'));
	// }
}
