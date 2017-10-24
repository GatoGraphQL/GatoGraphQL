<?php
class Wassup_ServerSide_CustomFunctions {

	//-------------------------------------------------
	// PUBLIC FUNCTIONS
	//-------------------------------------------------

	function addPageSectionIds(&$args) {
	
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		$popJSRuntimeManager = PoP_ServerSide_Libraries_Factory::get_jsruntime_instance();

		$domain = $args['domain'];
		$pageSection = $args['pageSection'];
		$template = $args['template'];
		$pssId = $popManager->getSettingsId($pageSection);
		// $psId = $pageSection->attr('id');

		// if ($psId == GD_TEMPLATEID_PAGESECTIONID_HOVER) {
		if ($pssId == GD_TEMPLATE_PAGESECTION_HOVER) {

			$psId = GD_TEMPLATEID_PAGESECTIONID_HOVER;
			$popJSRuntimeManager->addPageSectionId($domain, $pssId, $template, $psId, 'closehover');
		}	
		// else if ($psId == GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR) {
		else if ($pssId == GD_TEMPLATE_PAGESECTION_NAVIGATOR) {

			$psId = GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR;
			$popJSRuntimeManager->addPageSectionId($domain, $pssId, $template, $psId, 'closenavigator');
		}	
		// else if ($psId == GD_TEMPLATEID_PAGESECTIONID_ADDONS) {
		else if ($pssId == GD_TEMPLATEID_PAGESECTIONSETTINGSID_ADDONS) {

			$psId = GD_TEMPLATEID_PAGESECTIONID_ADDONS;
			$popJSRuntimeManager->addPageSectionId($domain, $pssId, $template, $psId, 'window-fullsize');
			$popJSRuntimeManager->addPageSectionId($domain, $pssId, $template, $psId, 'window-maximize');
			$popJSRuntimeManager->addPageSectionId($domain, $pssId, $template, $psId, 'window-minimize');
		}	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
if (PoP_Frontend_ServerUtils::use_serverside_rendering()) {
	$wassup_serverside_customfunctions = new Wassup_ServerSide_CustomFunctions();
	$popJSLibraryManager = PoP_ServerSide_Libraries_Factory::get_jslibrary_instance();
	$popJSLibraryManager->register($wassup_serverside_customfunctions, array('addPageSectionIds'));
}