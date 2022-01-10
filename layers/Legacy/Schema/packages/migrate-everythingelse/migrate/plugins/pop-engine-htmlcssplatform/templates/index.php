<?php

use PoP\ConfigurationComponentModel\Facades\Engine\EngineFacade;

$engine = EngineFacade::getInstance();
// $engine->outputResponse();
$engine->maybeRedirectAndExit();
$engine->generateData();

// $vars = ApplicationState::getVars();
// include \PoP\Root\App::getState('theme-path').'/mainpagesection.php';

// Allow PoP SSR to inject the server-side rendered HTML

// $class = PoP_HTMLCSSPlatformEngine_Module_Utils::getMergeClass([PoP_Module_Processor_PageSections::class, PoP_Module_Processor_PageSections::MODULE_PAGESECTION_TOP]);
// $cached_settings = $engine->isCachedSettings() ? "true" : "false";

get_header();
?>

	<div id="<?php echo POP_ID_ENTRY ?>">
		<?php echo PoP_HTMLCSSPlatformEngine_Module_Utils::getMainHtml() ?>
	</div>

<?php
get_footer();

// // Allow extra functionalities. Eg: Save the logged-in user meta information
// $engine->triggerOutputHooks();
