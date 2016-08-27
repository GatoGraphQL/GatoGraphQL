<?php

define('POP_MIN_VERSION', 1.0);

class MLA_PoP_Validation {

	function validate(){
		
		$success = true;

		// Validate PoP
		if(!defined('POP_VERSION')){

			add_action('admin_notices',array(&$this,'pop_install_warning'));
			add_action('network_admin_notices',array(&$this,'pop_install_warning'));
			$success = false;
		}
		elseif(!defined('POP_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif(POP_MIN_VERSION > POP_VERSION){
			
			add_action('admin_notices',array(&$this,'pop_version_warning'));
			add_action('network_admin_notices',array(&$this,'pop_version_warning'));
		}

		// Validate plug-in
		if(!defined('CURRENT_MLA_VERSION')){

			add_action('admin_notices',array(&$this,'plugin_install_warning'));
			add_action('network_admin_notices',array(&$this,'plugin_install_warning'));
			$success = false;
		}
		elseif(MLAPOP_MLA_VERSION > CURRENT_MLA_VERSION){
			
			add_action('admin_notices',array(&$this,'plugin_version_warning'));
			add_action('network_admin_notices',array(&$this,'plugin_version_warning'));
		}

		return $success;	
	}
	function admin_notice($message){
		?>
		<div class="error"><p><?php echo $message ?><br/><em><?php _e('Only admins see this message.','ps-pop'); ?></em></p></div>
		<?php
	}
	function pop_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP—Platform of Platforms</b> is not installed/activated. Without it, <b>Media Library Assistant for PoP</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function pop_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>PoP—Platform of Platforms</b> installed, or otherwise <b>PhotoSwipe PoP</b> might not function properly.','ps-pop'));
	}
	function plugin_install_warning(){
		
		$this->admin_notice(__('Error: <b>Media Library Assistant</b> is not installed/activated. Without it, <b>Media Library Assistant for PoP</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function plugin_version_warning(){
		
		$this->admin_notice(__('Warning: please make sure to have the <a href="http://wordpress.org/extend/plugins/pop/">latest version</a> of <b>Media Library Assistant</b> installed, or otherwise <b>Media Library Assistant for PoP</b> might not function properly.','ps-pop'));
	}
}
