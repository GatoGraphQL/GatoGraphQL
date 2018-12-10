<?php
 
define ('GD_DATALOADER_SINGLE', 'single');

class GD_Dataloader_Single extends GD_Dataloader_PostBase {

	use GD_Dataloader_SingleTrait;

	function get_name() {
    
		return GD_DATALOADER_SINGLE;
	}

	// /**
 //     * Function to override
 //     */
	// function execute_get_data($ids) {
	
	// 	$vars = PoP_ModuleManager_Vars::get_vars();
	// 	$post = $vars['global-state']['queried-object'];
	// 	return array($post);
	// }
}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_Single();