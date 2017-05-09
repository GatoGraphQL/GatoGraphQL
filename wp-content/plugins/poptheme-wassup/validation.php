<?php

define('POP_COREPROCESSORS_MIN_VERSION', 0.1);
define('POP_EMAILSENDER_MIN_VERSION', 0.1);
define('EM_POPPROCESSORS_MIN_VERSION', 0.1);
define('URE_POPPROCESSORS_MIN_VERSION', 0.1);
define('PHOTOSWIPEPOP_MIN_VERSION', 0.1);

class PoPTheme_Wassup_Validation {

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

		if(!defined('POP_EMAILSENDER_VERSION')){

			add_action('admin_notices',array($this,'emailsender_install_warning'));
			add_action('network_admin_notices',array($this,'emailsender_install_warning'));
			$success = false;
		}
		elseif(!defined('POP_EMAILSENDER_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(POP_EMAILSENDER_MIN_VERSION > POP_EMAILSENDER_VERSION){
			
			add_action('admin_notices',array($this,'emailsender_version_warning'));
			add_action('network_admin_notices',array($this,'emailsender_version_warning'));
		}

		// Events Manager
		if(!defined('EM_POPPROCESSORS_VERSION')){

			add_action('admin_notices',array($this,'em_install_warning'));
			add_action('network_admin_notices',array($this,'em_install_warning'));
			$success = false;
		}
		elseif(!defined('EM_POPPROCESSORS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(EM_POPPROCESSORS_MIN_VERSION > EM_POPPROCESSORS_VERSION){
			
			add_action('admin_notices',array($this,'em_version_warning'));
			add_action('network_admin_notices',array($this,'em_version_warning'));
		}

		// User Role Editor
		if(!defined('URE_POPPROCESSORS_VERSION')){

			add_action('admin_notices',array($this,'ure_install_warning'));
			add_action('network_admin_notices',array($this,'ure_install_warning'));
			$success = false;
		}
		elseif(!defined('URE_POPPROCESSORS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(URE_POPPROCESSORS_MIN_VERSION > URE_POPPROCESSORS_VERSION){
			
			add_action('admin_notices',array($this,'ure_version_warning'));
			add_action('network_admin_notices',array($this,'ure_version_warning'));
		}

		// PhotoSwipe PoP
		if(!defined('PHOTOSWIPEPOP_VERSION')){

			add_action('admin_notices',array($this,'ps_install_warning'));
			add_action('network_admin_notices',array($this,'ps_install_warning'));
			$success = false;
		}
		elseif(!defined('PHOTOSWIPEPOP_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(PHOTOSWIPEPOP_MIN_VERSION > PHOTOSWIPEPOP_VERSION){
			
			add_action('admin_notices',array($this,'ps_version_warning'));
			add_action('network_admin_notices',array($this,'ps_version_warning'));
		}

		return $success;	
	}

	function admin_notice($message){
		?>
		<div class="error"><p><?php echo $message ?><br/><em><?php _e('Only admins see this message.','ps-pop'); ?></em></p></div>
		<?php
	}
	function install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Core Processors</b> is not installed/activated. Without it, <b>PoP Theme: Wassup</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>PoP Core Processors</b> installed, or otherwise <b>PoP Theme: Wassup</b> might not function properly.','ps-pop'));
	}
	function emailsender_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Email Sender</b> is not installed/activated. Without it, <b>PoP Theme: Wassup</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function emailsender_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>PoP Email Sender</b> installed, or otherwise <b>PoP Theme: Wassup</b> might not function properly.','ps-pop'));
	}
	function em_install_warning(){
		
		$this->admin_notice(__('Error: <b>Events Manager PoP Processors</b> is not installed/activated. Without it, <b>PoP Theme: Wassup</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function em_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>Events Manager PoP Processors</b> installed, or otherwise <b>PoP Theme: Wassup</b> might not function properly.','ps-pop'));
	}
	function ure_install_warning(){
		
		$this->admin_notice(__('Error: <b>User Role Editor PoP Processors</b> is not installed/activated. Without it, <b>PoP Theme: Wassup</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function ure_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>User Role Editor PoP Processors</b> installed, or otherwise <b>PoP Theme: Wassup</b> might not function properly.','ps-pop'));
	}
	function ps_install_warning(){
		
		$this->admin_notice(__('Error: <b>PhotoSwipe for PoP</b> is not installed/activated. Without it, <b>PoP Theme: Wassup</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function ps_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>PhotoSwipe for PoP</b> installed, or otherwise <b>PoP Theme: Wassup</b> might not function properly.','ps-pop'));
	}
}
