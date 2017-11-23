<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GetPoPDemo_ResourceLoader_Hooks {

	function __construct() {

		add_filter(
			'PoPTheme_Wassup_ResourceLoaderProcessor_Hooks:css-resources:collapse-hometop',
			array($this, 'get_collapse_hometop_template_id')
		);
	}

	function get_collapse_hometop_template_id($template_id) {

		return GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoPDemo_ResourceLoader_Hooks();
