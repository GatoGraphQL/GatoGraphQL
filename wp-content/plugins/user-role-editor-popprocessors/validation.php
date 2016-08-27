<?php

define('URE_MIN_VERSION', '4.19.1');
define('POP_COREPROCESSORS_MIN_VERSION', 0.1);

class URE_PoPProcessors_Validation {

	function validate(){
		
		$success = true;

		if(!defined('URE_VERSION')){

			add_action('admin_notices',array($this,'install_warning'));
			add_action('network_admin_notices',array($this,'install_warning'));
			$success = false;
		}
		elseif(URE_MIN_VERSION > URE_VERSION){
			
			add_action('admin_notices',array($this,'version_warning'));
			add_action('network_admin_notices',array($this,'version_warning'));
		}

		if(!defined('POP_COREPROCESSORS_VERSION')){

			add_action('admin_notices',array($this,'core_install_warning'));
			add_action('network_admin_notices',array($this,'core_install_warning'));
			$success = false;
		}
		elseif(!defined('POP_COREPROCESSORS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(POP_COREPROCESSORS_MIN_VERSION > POP_COREPROCESSORS_VERSION){
			
			add_action('admin_notices',array($this,'core_version_warning'));
			add_action('network_admin_notices',array($this,'core_version_warning'));
		}

		return $success;	
	}
	function admin_notice($message){
		?>
		<div class="error"><p><?php echo $message ?><br/><em><?php _e('Only admins see this message.','ps-pop'); ?></em></p></div>
		<?php
	}
	function install_warning(){
		
		$this->admin_notice(__('Error: <b>User Role Editor</b> is not installed/activated. Without it, <b>User Role Editor PoP Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>User Role Editor</b> installed, or otherwise <b>User Role Editor PoP Processors</b> might not function properly.','ps-pop'));
	}
	function core_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Core Processors</b> is not installed/activated. Without it, <b>User Role Editor PoP Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function core_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>PoP Core Processors</b> installed, or otherwise <b>User Role Editor PoP Processors</b> might not function properly.','ps-pop'));
	}
}
