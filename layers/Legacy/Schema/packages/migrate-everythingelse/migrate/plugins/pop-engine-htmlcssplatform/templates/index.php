<?php

use PoP\ConfigurationComponentModel\Facades\Engine\EngineFacade;
use PoP\EngineWP\Facades\HelperServices\TemplateHelpersFacade;
use PoP\Root\App;

$engine = EngineFacade::getInstance();
$engine->maybeRedirectAndExit();

$templateHelpers = TemplateHelpersFacade::getInstance();
include $templateHelpers->getGenerateDataAndPrepareResponseTemplateFile();

/**
 * Send the headers to the client
 */
App::getResponse()->sendHeaders();

get_header();
?>

	<div id="<?php echo POP_ID_ENTRY ?>">
		<?php /** @todo Print the HTML code through Response in App, instead of PoP_HTMLCSSPlatformEngine_Module_Utils::getMainHtml() */ ?>
		<?php echo App::getResponse()->getContent() ?>
		<?php echo PoP_HTMLCSSPlatformEngine_Module_Utils::getMainHtml() ?>
	</div>

<?php
get_footer();
