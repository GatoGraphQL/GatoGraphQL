<?php
namespace PoP\CMSModel;
 
define ('GD_DATALOADER_HOMESTATICPAGE', 'homestaticpage');

class Dataloader_HomeStaticPage extends Dataloader_PostBase {

	function get_name() {
    
		return GD_DATALOADER_HOMESTATICPAGE;
	}

	function get_dbobject_ids($data_properties) {
	
		$cmsapi = \PoP\CMS\FunctionAPI_Factory::get_instance();
		if ($page_id = $cmsapi->get_home_static_page()) {

			return array($page_id);
		}
		return array();
	}
}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Dataloader_HomeStaticPage();