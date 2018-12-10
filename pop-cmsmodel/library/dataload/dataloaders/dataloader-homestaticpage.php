<?php
 
define ('GD_DATALOADER_HOMESTATICPAGE', 'homestaticpage');

class GD_Dataloader_HomeStaticPage extends GD_Dataloader_PostBase {

	function get_name() {
    
		return GD_DATALOADER_HOMESTATICPAGE;
	}

	function get_dbobject_ids($data_properties) {
	
		$cmsapi = PoP_CMS_FunctionAPI_Factory::get_instance();
		if ($page_id = $cmsapi->get_home_static_page()) {

			return array($page_id);
		}
		return array();
	}
}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_HomeStaticPage();