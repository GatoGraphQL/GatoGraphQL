<?php

define('POPTHEME_WASSUP_SECTIONPROCESSORS_MIN_VERSION', 0.1);
define('GETPOPDEMO_PROCESSORS_MIN_VERSION', 0.1);

class GetPoPDemo_Validation {

	function validate(){

		$success = true;
		if(!defined('POPTHEME_WASSUP_SECTIONPROCESSORS_VERSION')){

			add_action('admin_notices',array($this,'install_warning'));
			add_action('network_admin_notices',array($this,'install_warning'));
			// Comment Leo: allow the theme to initialize, so that we can access the back-end in case of error, eg: to enable the needed plug-ins
			// $success = false;
		}
		elseif(!defined('POPTHEME_WASSUP_SECTIONPROCESSORS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			// Comment Leo: allow the theme to initialize, so that we can access the back-end in case of error, eg: to enable the needed plug-ins
			// $success = false;
		}
		elseif(POPTHEME_WASSUP_SECTIONPROCESSORS_VERSION < POPTHEME_WASSUP_SECTIONPROCESSORS_MIN_VERSION){
			
			add_action('admin_notices',array($this,'version_warning'));
			add_action('network_admin_notices',array($this,'version_warning'));
		}

		if(!defined('GETPOPDEMO_ENVIRONMENT_VERSION')){

			add_action('admin_notices',array($this,'plugins_env_warning'));
			add_action('network_admin_notices',array($this,'plugins_env_warning'));
			// Comment Leo: allow the theme to initialize, so that we can access the back-end in case of error, eg: to enable the needed plug-ins
			// $success = false;
		}
		else {

			if(!defined('GETPOPDEMO_PROCESSORS_VERSION')){

				add_action('admin_notices',array($this,'processorsdemo_install_warning'));
				add_action('network_admin_notices',array($this,'processorsdemo_install_warning'));
				// Comment Leo: allow the theme to initialize, so that we can access the back-end in case of error, eg: to enable the needed plug-ins
				// $success = false;
			}
			elseif(!defined('GETPOPDEMO_PROCESSORS_INITIALIZED')){

				// The admin notice will come from another failing plug-in, no need to repeat it here
				// Comment Leo: allow the theme to initialize, so that we can access the back-end in case of error, eg: to enable the needed plug-ins
				// $success = false;
			}
			elseif(GETPOPDEMO_PROCESSORS_VERSION < GETPOPDEMO_PROCESSORS_MIN_VERSION){
				
				add_action('admin_notices',array($this,'processorsdemo_version_warning'));
				add_action('network_admin_notices',array($this,'processorsdemo_version_warning'));
			}
		}

		return $success;
	}

	function admin_notice($message){
		?>
		<div class="error"><p><?php echo $message ?><br/><em><?php _e('Only admins see this message.','getpop-demo'); ?></em></p></div>
		<?php
	}
	function install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Theme: Wassup Processors</b> is not installed/activated. Without it, <b>GetPoP Demo</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','getpop-demo'));
	}
	function version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>PoP Theme: Wassup Processors</b> installed, or otherwise <b>GetPoP Demo</b> might not function properly.','getpop-demo'));
	}
	function plugins_env_warning(){
		
		$this->admin_notice(__('Error: <b>GetPoP Demo Environment Constants</b> must be installed/activated. Without it, <b>GetPoP Demo</b> will not work. Please install the corresponding plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','getpop-demo'));
	}
	function processorsdemo_install_warning(){
		
		$this->admin_notice(__('Error: <b>GetPoP Demo Processors</b> is not installed/activated. Without it, <b>GetPoP Demo</b> will not work. Please install this plugin from your plugin installer or download it <a href="https://github.com/leoloso/PoP/">from here</a>.','getpop-demo'));
	}
	function processorsdemo_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="https://github.com/leoloso/PoP/">latest version</a> of <b>GetPoP Demo Processors</b> installed, or otherwise <b>GetPoP Demo</b> might not function properly.','getpop-demo'));
	}
}
