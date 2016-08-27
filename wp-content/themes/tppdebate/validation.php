<?php

define('POPTHEME_WASSUP_SECTIONPROCESSORS_MIN_VERSION', 0.1);
define('POPTHEME_WASSUP_VOTINGPROCESSORS_MIN_VERSION', 0.1);

class TPPDebate_Validation {

	function validate(){

		$success = true;

		// Validate that MESYM Processors is installed. These are needed for Discussions/Announcements/Stories
		if(!defined('POPTHEME_WASSUP_SECTIONPROCESSORS_VERSION')){

			add_action('admin_notices',array($this,'wassup_install_warning'));
			add_action('network_admin_notices',array($this,'wassup_install_warning'));
			// Comment Leo: allow the theme to initialize, so that we can access the back-end in case of error, eg: to enable the needed plug-ins
			// $success = false;
		}
		elseif(!defined('POPTHEME_WASSUP_SECTIONPROCESSORS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			// Comment Leo: allow the theme to initialize, so that we can access the back-end in case of error, eg: to enable the needed plug-ins
			// $success = false;
		}
		elseif(POPTHEME_WASSUP_SECTIONPROCESSORS_VERSION < POPTHEME_WASSUP_SECTIONPROCESSORS_MIN_VERSION){
			
			add_action('admin_notices',array($this,'wassup_version_warning'));
			add_action('network_admin_notices',array($this,'wassup_version_warning'));
		}

		// Validate that TPPDebate Processors is installed
		if(!defined('POPTHEME_WASSUP_VOTINGPROCESSORS_VERSION')){

			add_action('admin_notices',array($this,'tpp_install_warning'));
			add_action('network_admin_notices',array($this,'tpp_install_warning'));
			// Comment Leo: allow the theme to initialize, so that we can access the back-end in case of error, eg: to enable the needed plug-ins
			// $success = false;
		}
		elseif(!defined('POPTHEME_WASSUP_VOTINGPROCESSORS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			// Comment Leo: allow the theme to initialize, so that we can access the back-end in case of error, eg: to enable the needed plug-ins
			// $success = false;
		}
		elseif(POPTHEME_WASSUP_VOTINGPROCESSORS_VERSION < POPTHEME_WASSUP_VOTINGPROCESSORS_MIN_VERSION){
			
			add_action('admin_notices',array($this,'tpp_version_warning'));
			add_action('network_admin_notices',array($this,'tpp_version_warning'));
		}

		if(!defined('TPPDEBATE_MY_ENVIRONMENT_VERSION') && !defined('TPPDEBATE_AR_ENVIRONMENT_VERSION') && !defined('TPPDEBATE_ENVIRONMENT_VERSION')){

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
	function wassup_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Theme: Wassup Processors</b> is not installed/activated. Without it, <b>MESYM</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function wassup_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>PoP Theme: Wassup Processors</b> installed, or otherwise <b>MESYM</b> might not function properly.','ps-pop'));
	}
	function tpp_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP Theme: TPPDebate Processors</b> is not installed/activated. Without it, <b>TPPDebate</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function tpp_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>PoP Theme: TPPDebate Processors</b> installed, or otherwise <b>TPPDebate</b> might not function properly.','ps-pop'));
	}
	function plugins_env_warning(){
		
		$this->admin_notice(__('Error: either <b>TPP Debate Malaysia Environment Constants</b>, or <b>TPP Debate Argentina Environment Constants</b> or <b>TPP Debate Environment Constants</b> must be installed and activated. Without it, <b>TPP Debate</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
}
