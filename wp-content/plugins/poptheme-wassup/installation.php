<?php
class PoPTheme_Wassup_Installation {

	function system_generate() {

		global $popthemewassup_backgroundimage_filegenerator, $popthemewassup_feedthumb_filegenerator;
		$popthemewassup_backgroundimage_filegenerator->generate();
		$popthemewassup_feedthumb_filegenerator->generate();
	}

	function system_activateplugins() {

		// Activate plugins: do it immediately
		$this->activate_plugins();
	}

	protected function activate_plugins() {

		// Plugins needed by the website. Check the website version, if it's the one indicated,
		// then proceed to install the required plugin
		$plugin_version = array();
		
		// Public Post Preview PoP
		$plugin_version['public-post-preview-pop'] = POPTHEME_WASSUP_PLUGINACTIVATION_PPPPOP_VERSION;

		// Google Analytics Dashboard for WP PoP
		$plugin_version['google-analytics-dashboard-for-wp-pop'] = POPTHEME_WASSUP_PLUGINACTIVATION_GADWPPOP_VERSION;

		// PoP FrontendEngine AWS
		$plugin_version['pop-frontendengine-aws'] = POPTHEME_WASSUP_PLUGINACTIVATION_FRONTENDENGINEAWS_VERSION;

		// Disable REST API
		$plugin_version['disable-json-api'] = POPTHEME_WASSUP_PLUGINACTIVATION_DISABLERESTAPI_VERSION;

		// PoP Cluster ResourceLoader
		$plugin_version['pop-cluster-resourceloader'] = POPTHEME_WASSUP_PLUGINACTIVATION_CLUSTERRESOURCELOADER_VERSION;

		// PoP Cluster ResourceLoader for AWS
		$plugin_version['pop-cluster-resourceloader-aws'] = POPTHEME_WASSUP_PLUGINACTIVATION_CLUSTERRESOURCELOADERAWS_VERSION;

		// PoP Generic Forms
		$plugin_version['pop-genericforms'] = POPTHEME_WASSUP_PLUGINACTIVATION_POPGENERICFORMS_VERSION;

		// PoP Generic Forms Processors
		$plugin_version['pop-genericforms-processors'] = POPTHEME_WASSUP_PLUGINACTIVATION_POPGENERICFORMSPROCESSORS_VERSION;

		// Gravity Forms for PoP
		$plugin_version['gravityforms-pop'] = POPTHEME_WASSUP_PLUGINACTIVATION_GFPOP_VERSION;
		
		// Gravity Forms for PoP Generic Forms
		$plugin_version['gravityforms-pop-genericforms'] = POPTHEME_WASSUP_PLUGINACTIVATION_GFPOPGENERICFORMS_VERSION;

		// Iterate all plugins and check what version they require to be installed. If it matches the current version => activate
		$version = pop_version();
		foreach ($plugin_version as $plugin => $activate_version) {

			if ($activate_version == $version) {

				run_activate_plugin("${plugin}/${plugin}.php");
			}
		}
	}
}