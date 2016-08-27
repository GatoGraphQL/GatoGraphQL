<?php

define('POPTHEME_WASSUP_SECTIONPROCESSORS_MIN_VERSION', 0.1);

class AgendaUrbana_Validation {

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

		if(!defined('AGENDAURBANA_ENVIRONMENT_VERSION')){

			add_action('admin_notices',array($this,'plugins_env_warning'));
			add_action('network_admin_notices',array($this,'plugins_env_warning'));
			// Comment Leo: allow the theme to initialize, so that we can access the back-end in case of error, eg: to enable the needed plug-ins
			// $success = false;
		}
		
		return $success;
	}

	function admin_notice($message){
		?>
		<div class="error"><p><?php echo $message ?><br/><em><?php _e('Only admins see this message.','ps-pop'); ?></em></p></div>
		<?php
	}
	function install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Theme: Wassup Processors</b> is not installed/activated. Without it, <b>Agenda Urbana</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>PoP Theme: Wassup Processors</b> installed, or otherwise <b>Agenda Urbana</b> might not function properly.','ps-pop'));
	}
	function plugins_env_warning(){
		
		$this->admin_notice(__('Error: <b>Agenda Urbana Environment Constants</b> is not installed/activated. Without it, <b>Agenda Urbana</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
}
