<?php

/**---------------------------------------------------------------------------------------------------------------
 * Uniqueblocks
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('GD_TemplateManager_Utils:get_unique_blockgroups', 'get_genericforms_custom_unique_blockgroups', 0);
function get_genericforms_custom_unique_blockgroups($blockgroups) {

	$blockgroups[] = GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL;
	return $blockgroups;
}
