<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_AUTHOR', 'author');

class Dataloader_Author extends Dataloader_UserBase {

	function get_name() {
    
		return GD_DATALOADER_AUTHOR;
	}

	function get_dbobject_ids($data_properties) {
	
		$vars = \PoP\Engine\Engine_Vars::get_vars();
		$author = $vars['global-state']['queried-object-id'];
		return array($author);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Dataloader_Author();