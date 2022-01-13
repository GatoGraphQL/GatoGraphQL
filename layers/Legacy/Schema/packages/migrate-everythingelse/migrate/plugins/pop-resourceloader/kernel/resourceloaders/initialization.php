<?php
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;

class PoPWebPlatform_ResourceLoader_Initialization {

	public function __construct() {

		// Wait until the system is initialized, so we can access the application state
		// These 2 functions (register and localize) are separated into 2 calls, so that they can independently 
		// be unhooked (eg: by Service Workers)
		\PoP\Root\App::getHookManager()->addAction('popcms:enqueueScripts', array($this, 'registerScripts'), 50);
		\PoP\Root\App::getHookManager()->addAction('popcms:enqueueScripts', array($this, 'localizeScripts'), 70);
		\PoP\Root\App::getHookManager()->addAction('popcms:printStyles', array($this, 'registerStyles'), 50);
	}

	function registerScripts() {

		// Register the scripts from the Resource Loader on the current request
		// Only if loading the frame, and it is configured to use code splitting
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
		if (!$cmsapplicationapi->isAdminPanel() && RequestUtils::loadingSite() && PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {

			global $popwebplatform_resourceloader_scriptsandstyles_registration;
			$popwebplatform_resourceloader_scriptsandstyles_registration->registerScripts();
		}
	}

	function registerStyles() {

		// Register the scripts from the Resource Loader on the current request
		// Only if loading the frame, and it is configured to use code splitting
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
		if (!$cmsapplicationapi->isAdminPanel() && RequestUtils::loadingSite() && PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {

			global $popwebplatform_resourceloader_scriptsandstyles_registration;
			$popwebplatform_resourceloader_scriptsandstyles_registration->registerStyles();
		}
	}

	function localizeScripts() {

		// Only if loading the frame, and it is configured to use code splitting
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
		if (!$cmsapplicationapi->isAdminPanel() && RequestUtils::loadingSite() && PoP_ResourceLoader_ServerUtils::useCodeSplitting()) {

			// Also localize the scripts
			global $pop_jsresourceloaderprocessor_manager;
			$pop_jsresourceloaderprocessor_manager->localizeScripts();
		}
	}
}

/**
 * Initialization
 */
global $popwebplatform_resourceloader_initialization;
$popwebplatform_resourceloader_initialization = new PoPWebPlatform_ResourceLoader_Initialization();
