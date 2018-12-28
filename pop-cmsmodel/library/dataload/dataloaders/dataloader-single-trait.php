<?php
namespace PoP\CMSModel;
 
trait Dataloader_SingleTrait {

	function get_dbobject_ids($data_properties) {
	
		// Simply return the global $post ID. 
		$vars = \PoP\Engine\Engine_Vars::get_vars();
		return array($vars['global-state']['queried-object-id']);
	}
}
