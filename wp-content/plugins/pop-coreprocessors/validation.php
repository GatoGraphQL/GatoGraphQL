<?php

define('POP_BOOTSTRAPPROCESSORS_MIN_VERSION', 0.1);

class PoP_CoreProcessors_Validation {

	function validate(){

		$success = true;
		if (!defined('POP_BOOTSTRAPPROCESSORS_VERSION')) {

			add_action('admin_notices',array($this,'install_warning'));
			add_action('network_admin_notices',array($this,'install_warning'));
			$success = false;
		}
		elseif(!defined('POP_BOOTSTRAPPROCESSORS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif (POP_BOOTSTRAPPROCESSORS_MIN_VERSION > POP_BOOTSTRAPPROCESSORS_VERSION) {
			
			add_action('admin_notices',array($this,'version_warning'));
			add_action('network_admin_notices',array($this,'version_warning'));
			$success = false;
		}

		return $success;	
	}
	function admin_notice($message){
		?>
		<div class="error"><p><?php echo $message ?><br/><em><?php _e('Only admins see this message.','ps-pop'); ?></em></p></div>
		<?php
	}
	function install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Bootstrap Processors</b> is not installed/activated. Without it, <b>PoP Core Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function version_warning(){
		
		$this->admin_notice(
			sprintf(
				__('Error: this version of <b>PoP Core Processors</b> requires version %s or bigger of <b>PoP Bootstrap Processors</b>. Please update this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'),
				POP_BOOTSTRAPPROCESSORS_MIN_VERSION
			)
		);
	}
}
