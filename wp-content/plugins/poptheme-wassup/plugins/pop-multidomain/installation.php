<?php
class PoPTheme_Wassup_Multidomain_Installation {

	function __construct() {

		add_action('PoP:system-generate', array($this,'system_generate'));
	}

	function system_generate() {

		global $popthemewassup_multidomainstyles_filegenerator;
		$popthemewassup_multidomainstyles_filegenerator->generate();
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_Multidomain_Installation();
