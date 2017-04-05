<?php

define('POP_COREPROCESSORS_MIN_VERSION', 0.1);
define('POP_CDN_MIN_VERSION', 0.1);

class PoP_CDNCore_Validation {

	function validate(){

		$success = true;
		if (!defined('POP_CDN_VERSION')) {

			add_action('admin_notices',array($this,'cdn_install_warning'));
			add_action('network_admin_notices',array($this,'cdn_install_warning'));
			$success = false;
		}
		elseif(!defined('POP_CDN_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif (POP_CDN_MIN_VERSION > POP_CDN_VERSION) {
			
			add_action('admin_notices',array($this,'cdn_version_warning'));
			add_action('network_admin_notices',array($this,'cdn_version_warning'));
			$success = false;
		}

		if (!defined('POP_COREPROCESSORS_VERSION')) {

			add_action('admin_notices',array($this,'install_warning'));
			add_action('network_admin_notices',array($this,'install_warning'));
			$success = false;
		}
		elseif(!defined('POP_COREPROCESSORS_INITIALIZED')){

			// The admin notice will come from another failing plug-in, no need to repeat it here
			$success = false;
		}
		elseif (POP_COREPROCESSORS_MIN_VERSION > POP_COREPROCESSORS_VERSION) {
			
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
		
		$this->admin_notice(__('Error: <b>PoP Core Processors</b> is not installed/activated. Without it, <b>PoP CDNCore</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function version_warning(){
		
		$this->admin_notice(
			sprintf(
				__('Error: this version of <b>PoP CDNCore</b> requires version %s or bigger of <b>PoP Core Processors</b>. Please update this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'),
				POP_COREPROCESSORS_MIN_VERSION
			)
		);
	}
	function cdn_install_warning(){
		
		$this->admin_notice(__('Error: <b>PoP CDN</b> is not installed/activated. Without it, <b>PoP CDN for Core Processors</b> will not work. Please install this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'));
	}
	function cdn_version_warning(){
		
		$this->admin_notice(
			sprintf(
				__('Error: this version of <b>PoP CDN for Core Processors</b> requires version %s or bigger of <b>PoP CDN</b>. Please update this plugin from your plugin installer or download it <a href="http://wordpress.org/extend/plugins/pop/">from here</a>.','ps-pop'),
				POP_COREPROCESSORS_MIN_VERSION
			)
		);
	}
}
