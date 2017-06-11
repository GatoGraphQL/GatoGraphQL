<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServerSideRendering {

	private $enabled, $templatesource_paths, $json, $renderers;
	
	function __construct() {

		// Add myself as the instance in the factory
		PoP_ServerSideRendering_Factory::set_instance($this);
	
		// Initialize variables
		$this->enabled = PoP_Frontend_ServerUtils::use_serverside_rendering();
		$this->templatesource_paths = array();
		$this->renderers = array();
	}
	
	function add_templatesource_path($template_source, $path) {
	
		$this->templatesource_paths[$template_source] = $path;
	}
	
	function get_json() {

		if (!$this->enabled) {
			return array();
		}
	
		// Obtain the JSON from the PoP_Engine
		if (!$this->json) {
			
			// The JSON is already encoded, as a String, so we must decode it to transformt it into an array
			$engine = PoP_Engine_Factory::get_instance();
			$this->json = json_decode($engine->json['json'], true);

			// Also initialize the popManager, so it will get all its private values from $json
			$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
			$popManager->init();

			// Because $popManager modified the settings (eg: added the topLevelSettings, etc), then we gotta update the local $json object accordingly
			$this->json['settings'] = $popManager->getMemory()['settings'];
		}

		return $this->json;
	}
	
	function get_json_settings() {
	
		if (!$this->enabled) {
			return array();
		}
	
		$json = $this->get_json();
		return $json['settings'];
	}
	
	function get_json_configuration() {
	
		if (!$this->enabled) {
			return array();
		}
	
		$settings = $this->get_json_settings();
		return $settings['configuration'];
	}
	
	function get_json_templatesources() {
	
		if (!$this->enabled) {
			return array();
		}
	
		$settings = $this->get_json_settings();
		return $settings['template-sources'];
	}
	
	protected function get_renderer($filename) {

		// If the file has already been included, then return it
		if ($renderer = $this->renderers[$filename]) {

			return $renderer;
		}

		// Otherwise, include the file, and store it for later use
		$this->renderers[$filename] = include($filename);
		return $this->renderers[$filename];
	}
	
	function get_templatesource_renderer($template_source) {

		if (!$this->enabled) {
			return null;
		}

		if (!$path = $this->templatesource_paths[$template_source]) {

			throw new Exception(sprintf('No path registered for $template_source \'%s\', for $template_id \'%s\' (%s)', $template_source, $template_id, full_url()));
		}
	
		return $this->get_renderer($path.'/'.$template_source.'.php');
	}
	
	function render_templatesource($template_source, $configuration) {

		if (!$this->enabled) {
			return '';
		}

		$renderer = $this->get_templatesource_renderer($template_source);

		// Render and return the html
		return $renderer($configuration);
	}
	
	function render_template($template_id, $configuration) {

		if (!$this->enabled) {
			return '';
		}
	
		if (!$template_id) {

			throw new Exception(sprintf('$template_id cannot be null (%s)', full_url()));
		}

		$template_sources = $this->get_json_templatesources();

		// If a template source is not defined, then it is the template itself (eg: the pageSection templates)
		$template_source = $template_sources[$template_id] ?? $template_id;

		// Render and return the html
		return $this->render_templatesource($template_source, $configuration);
	}
	
	function render_pagesection($pagesection_settings_id, $target = null) {
	
		if (!$this->enabled) {
			return '';
		}

		// If the target was provided, then check that the current page has that target to render the html
		// Eg: addons pageSection must have target "addons", if not do nothing
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!is_null($target) && $target != $vars['target']) {

			return '';
		}
	
		// The pageSection has its configuration right under key $pagesection_settings_id of the global configuration
		$configuration = $this->get_json_configuration();
		if (!$pagesection_configuration = $configuration[$pagesection_settings_id]) {

			throw new Exception(sprintf('No configuration in context for $pagesection_settings_id \'%s\' (%s)', $pagesection_settings_id, full_url()));
		}

		// Expand the JS Keys first, since the template key may be the compacted one
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$popManager->expandJSKeys($pagesection_configuration);
		if (!$pagesection_template_id = $pagesection_configuration[GD_JS_TEMPLATE]) {

			throw new Exception(sprintf('No template defined in context (%s)', full_url()));
		}
		
		return $this->render_template($pagesection_template_id, $pagesection_configuration)/*.'<script type="application/json" id="test">'.$this->json.'</script>'*/;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServerSideRendering();
