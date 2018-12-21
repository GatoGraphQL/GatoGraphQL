<?php
namespace PoP\CMSModel;
 
define ('GD_DATALOADER_SINGLE', 'single');

class Dataloader_Single extends Dataloader_PostBase {

	use Dataloader_SingleTrait;

	function get_name() {
    
		return GD_DATALOADER_SINGLE;
	}

	// /**
 //     * Function to override
 //     */
	// function execute_get_data($ids) {
	
	// 	$vars = \PoP\Engine\Engine_Vars::get_vars();
	// 	$post = $vars['global-state']['queried-object'];
	// 	return array($post);
	// }
}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Dataloader_Single();