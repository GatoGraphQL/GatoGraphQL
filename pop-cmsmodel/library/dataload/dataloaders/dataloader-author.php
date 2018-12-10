<?php

define ('GD_DATALOADER_AUTHOR', 'author');

class GD_Dataloader_Author extends GD_Dataloader_UserBase {

	function get_name() {
    
		return GD_DATALOADER_AUTHOR;
	}

	function get_dbobject_ids($data_properties) {
	
		$vars = PoP_ModuleManager_Vars::get_vars();
		$author = $vars['global-state']['queried-object-id'];
		return array($author);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_Author();