<?php

define('POP_COREPROCESSORS_MIN_VERSION', 0.1);
define('POP_GENERICFORMS_MIN_VERSION', 0.1);
define('GFPOP_MIN_VERSION', 0.1);

class PoP_GFPoPGenericForms_Validation {

	function validate() {

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

		if(!defined('POP_GENERICFORMS_VERSION')){

			add_action('admin_notices',array($this,'forms_install_warning'));
			add_action('network_admin_notices',array($this,'forms_install_warning'));
			$success = false;
		}
		elseif(!defined('POP_GENERICFORMS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(POP_GENERICFORMS_MIN_VERSION > POP_GENERICFORMS_VERSION){
			
			add_action('admin_notices',array($this,'forms_version_warning'));
			add_action('network_admin_notices',array($this,'forms_version_warning'));
		}

		if(!defined('GFPOP_VERSION')){

			add_action('admin_notices',array($this,'plugin_install_warning'));
			add_action('network_admin_notices',array($this,'plugin_install_warning'));
			$success = false;
		}
		elseif(!defined('GFPOP_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(GFPOP_MIN_VERSION > GFPOP_VERSION){
			
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
		
		$this->admin_notice(__('Error: <b>PoP Core Processors</b> is not installed/activated. Without it, <b>Gravity Forms implementation for PoP Generic Forms</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>PoP Core Processors</b> installed, or otherwise <b>Gravity Forms implementation for PoP Generic Forms</b> might not function properly.','ps-pop'));
	}
	function forms_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Generic Forms</b> is not installed/activated. Without it, <b>Gravity Forms implementation for PoP Generic Forms</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function forms_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>PoP Generic Forms</b> installed, or otherwise <b>Gravity Forms implementation for PoP Generic Forms</b> might not function properly.','ps-pop'));
	}
	function plugin_install_warning(){
		
		$this->admin_notice(__('Error: <b>Gravity Forms for PoP</b> is not installed/activated. Without it, <b>Gravity Forms implementation for PoP Generic Forms</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function plugin_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>Gravity Forms for PoP</b> installed, or otherwise <b>Gravity Forms implementation for PoP Generic Forms</b> might not function properly.','ps-pop'));
	}
}
