<?php
namespace PoP\CMSModel;
 
trait Dataloader_SingleTrait {

	function get_dbobject_ids($data_properties) {
	
		// Simply return the global $post ID. 
    	$cmsresolver = \PoP\CMS\ObjectPropertyResolver_Factory::get_instance();
		$vars = \PoP\Engine\Engine_Vars::get_vars();
		$post = $vars['global-state']['queried-object'];
		return array($cmsresolver->get_post_id($post));
	}
}
