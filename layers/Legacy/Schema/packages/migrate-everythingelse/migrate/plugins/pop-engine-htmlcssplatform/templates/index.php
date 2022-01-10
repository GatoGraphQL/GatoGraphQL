<?php

use PoP\ConfigurationComponentModel\Facades\Engine\EngineFacade;

$engine = EngineFacade::getInstance();
$engine->maybeRedirectAndExit();
$engine->generateData();

get_header();
?>

	<div id="<?php echo POP_ID_ENTRY ?>">
		<?php echo PoP_HTMLCSSPlatformEngine_Module_Utils::getMainHtml() ?>
	</div>

<?php
get_footer();
