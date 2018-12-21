<?php
namespace PoP\CMSModel;
 
define ('GD_DATALOADER_TAG', 'tag');

class Dataloader_Tag extends Dataloader_TagBase {

	function get_name() {
    
		return GD_DATALOADER_TAG;
	}
	
	function get_dbobject_ids($data_properties) {
	
		$vars = \PoP\Engine\Engine_Vars::get_vars();
		return array($vars['global-state']['queried-object-id']);
	}
}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Dataloader_Tag();