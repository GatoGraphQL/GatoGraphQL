<?php

define('POP_COREPROCESSORS_MIN_VERSION', 0.1);
define('GFPOP_GF_MIN_VERSION', 0.1);

class GFPoP_Validation {

	function validate(){

		$success = true;
		if(!defined('POP_COREPROCESSORS_VERSION')){

			add_action('admin_notices',array($this,'install_warning'));
			add_action('network_admin_notices',array($this,'install_warning'));
			$success = false;
		}
		elseif(!defined('POP_COREPROCESSORS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(POP_COREPROCESSORS_MIN_VERSION > POP_COREPROCESSORS_VERSION){
			
			add_action('admin_notices',array($this,'version_warning'));
			add_action('network_admin_notices',array($this,'version_warning'));
		}

		// Validate plug-in
		if (!class_exists("RGForms")) {

			add_action('admin_notices',array($this,'plugin_install_warning'));
			add_action('network_admin_notices',array($this,'plugin_install_warning'));
			$success = false;
		}
		elseif(GFPOP_GF_MIN_VERSION > GFForms::$version){
			
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
	function install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Core Processors</b> is not installed/activated. Without it, <b>PoP Generic Forms</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>PoP Core Processors</b> installed, or otherwise <b>PoP Generic Forms</b> might not function properly.','ps-pop'));
	}
	function plugin_install_warning(){
		
		$this->admin_notice(__('Error: <b>Gravity Forms</b> is not installed/activated. Without it, <b>Gravity Forms for PoP</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function plugin_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>Gravity Forms</b> installed, or otherwise <b>Gravity Forms for PoP</b> might not function properly.','ps-pop'));
	}
}
