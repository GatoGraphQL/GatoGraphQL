<?php

define('EMPOP_MIN_VERSION', 0.1);
define('POP_COREPROCESSORS_MIN_VERSION', 0.1);

class EM_PoPProcessors_Validation {

	function validate(){
		
		$success = true;

		if(!defined('EMPOP_VERSION')){

			add_action('admin_notices',array($this,'install_warning'));
			add_action('network_admin_notices',array($this,'install_warning'));
			$success = false;
		}
		elseif(!defined('EMPOP_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(EMPOP_MIN_VERSION > EMPOP_VERSION){
			
			add_action('admin_notices',array($this,'version_warning'));
			add_action('network_admin_notices',array($this,'version_warning'));
		}

		// Events Manager PoP Processors need the PoP Core Processors: 
		// eg, for the TypeaheadMap (typeahead defined in core processors), LocationsMap blockgroup, etc
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
		
		$this->admin_notice(__('Error: <b>Events Manager for PoP</b> is not installed/activated. Without it, <b>Events Manager PoP Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>Events Manager for PoP</b> installed, or otherwise <b>Events Manager Basic Modules for PoP</b> might not function properly.','ps-pop'));
	}
	function core_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Core Processors</b> is not installed/activated. Without it, <b>Events Manager PoP Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function core_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>PoP Core Processors</b> installed, or otherwise <b>Events Manager Basic Modules for PoP</b> might not function properly.','ps-pop'));
	}
}
