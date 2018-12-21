<?php
namespace PoP\Engine;

define('POP_ENGINE_POP_CMS_MIN_VERSION', 0.1);

class Validation {

	function validate() {
		
		$success = true;
		if (!defined('POP_CMS_VERSION')) {

			add_action('admin_notices', array($this, 'install_warning'));
			add_action('network_admin_notices', array($this, 'install_warning'));
			$success = false;
		}
		elseif (!defined('POP_CMS_INITIALIZED')) {

			add_action('admin_notices', array($this, 'initialize_warning'));
			add_action('network_admin_notices', array($this, 'initialize_warning'));
			$success = false;
		}
		elseif (POP_ENGINE_POP_CMS_MIN_VERSION > POP_CMS_VERSION) {
			
			add_action('admin_notices', array($this, 'version_warning'));
			add_action('network_admin_notices', array($this, 'version_warning'));
		}
		// Validate that there is at least one implementation of the CMS
		elseif (is_null(\PoP\CMS\FunctionAPI_Factory::get_instance())) {

			add_action('admin_notices', array($this, 'cmsimplementation_warning'));
			add_action('network_admin_notices', array($this, 'cmsimplementation_warning'));
			$success = false;
		}

		return $success;	
	}
	function initialize_warning() {
		
		$this->dependency_initialization_warning(
			__('PoP Engine', 'pop-engine'),
			__('PoP CMS', 'pop-engine')
		);
	}
	function install_warning() {
		
		$this->dependency_installation_warning(
			__('PoP Engine', 'pop-engine'),
			__('PoP CMS', 'pop-engine'),
			'https://github.com/leoloso/PoP'
		);
	}
	function version_warning() {
		
		$this->dependency_version_warning(
			__('PoP Engine', 'pop-engine'),
			__('PoP CMS', 'pop-engine'),
			'https://github.com/leoloso/PoP',
			POP_ENGINE_POP_CMS_MIN_VERSION
		);
	}
	function cmsimplementation_warning() {
		
		$this->dependency_implementation_warning(
			__('PoP Engine', 'pop-engine'),
			__('PoP CMS', 'pop-engine')
		);
	}
	protected function dependency_installation_warning($plugin, $dependency, $dependency_url){
		
		$this->admin_notice(
			sprintf(
				__('Error: %s','pop-engine-frontend'),
				sprintf(
					__('<strong>%s</strong> is not installed/activated. Without it, <strong>%s</strong> will not work. Please install this plugin from your plugin installer or download it <a href="%s" target="_blank">from here</a>.','pop-engine-frontend'),
					$dependency,
					$plugin,
					$dependency_url
				)
			)
		);
	}
	protected function dependency_initialization_warning($plugin, $dependency){
		
		$this->admin_notice(
			sprintf(
				__('Error: %s','pop-engine-frontend'),
				sprintf(
					__('<strong>%s</strong> is not initialized properly. As a consequence, <strong>%s</strong> has not been loaded.','pop-engine-frontend'),
					$dependency,
					$plugin
				)
			)
		);
	}
	protected function dependency_version_warning($plugin, $dependency, $dependency_url, $dependency_min_version){
		
		$this->admin_notice(
			sprintf(
				__('Error: %s','pop-engine-frontend'),
				sprintf(
					__('<strong>%s</strong> requires version %s or bigger of <strong>%s</strong>. Please update this plugin from your plugin installer or download it <a href="%s" target="_blank">from here</a>.','pop-engine-frontend'),
					$plugin,
					$dependency_min_version,
					$dependency,
					$dependency_url
				)
			)
		);
	}
	protected function dependency_implementation_warning($plugin, $dependency){
		
		$this->admin_notice(
			sprintf(
				__('Error: %s','pop-engine-frontend'),
				sprintf(
					__('An underlying CMS implementation for <strong>%s</strong> is not installed/activated. Without it, <strong>%s</strong> will not work.','pop-engine-frontend'),
					$dependency,
					$plugin,
					$dependency_url
				)
			)
		);
	}
	protected function admin_notice($message) {
		?>
		<div class="error">
			<p>
				<?php echo $message ?><br/>
				<em>
					<?php _e('Only admins see this message.', 'pop-engine-frontend'); ?>
				</em>
			</p>
		</div>
		<?php
	}
}
