<?php
 
define ('GD_DATALOADER_TAG', 'tag');

class GD_Dataloader_Tag extends GD_Dataloader_TagBase {

	function get_name() {
    
		return GD_DATALOADER_TAG;
	}
	
	function get_dbobject_ids($data_properties) {
	
		$vars = PoP_ModuleManager_Vars::get_vars();
		return array($vars['global-state']['queried-object-id']);
	}
}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_Tag();