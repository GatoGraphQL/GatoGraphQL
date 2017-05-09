<?php

define('POPTHEME_WASSUP_MIN_VERSION', 0.1);

class GetPoPDemo_Processors_Validation {

	function validate(){

		$success = true;
		if(!defined('POPTHEME_WASSUP_VERSION')){

			add_action('admin_notices',array($this,'install_warning'));
			add_action('network_admin_notices',array($this,'install_warning'));
			$success = false;
		}
		elseif(!defined('POPTHEME_WASSUP_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(POPTHEME_WASSUP_MIN_VERSION > POPTHEME_WASSUP_VERSION){
			
			add_action('admin_notices',array($this,'version_warning'));
			add_action('network_admin_notices',array($this,'version_warning'));
		}

		return $success;	
	}

	function admin_notice($message){
		?>
		<div class="error"><p><?php echo $message ?><br/><em><?php _e('Only admins see this message.','ps-pop'); ?></em></p></div>
		<?php
	}
	function install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Theme: Wassup</b> is not installed/activated. Without it, <b>GetPoP Demo Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','ps-pop'));
	}
	function version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>PoP Theme: Wassup</b> installed, or otherwise <b>GetPoP Demo Processors</b> might not function properly.','ps-pop'));
	}
}
