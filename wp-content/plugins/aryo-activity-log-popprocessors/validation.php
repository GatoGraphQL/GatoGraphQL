<?php

define('AAL_POP_MIN_VERSION', 0.1);
define('POP_COREPROCESSORS_MIN_VERSION', 0.1);

class AAL_PoPProcessors_Validation {

	function validate(){
		
		$success = true;

		if(!defined('AAL_POP_VERSION')){

			add_action('admin_notices',array($this,'install_warning'));
			add_action('network_admin_notices',array($this,'install_warning'));
			$success = false;
		}
		elseif(!defined('AAL_POP_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(AAL_POP_MIN_VERSION > AAL_POP_VERSION){
			
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
		
		$this->admin_notice(__('Error: <b>Aryo Activity Log for PoP</b> is not installed/activated. Without it, <b>Aryo Activity Log PoP Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>Aryo Activity Log for PoP</b> installed, or otherwise <b>Aryo Activity Log Basic Modules for PoP</b> might not function properly.','ps-pop'));
	}
}
