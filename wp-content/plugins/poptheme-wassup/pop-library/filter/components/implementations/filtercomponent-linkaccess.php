<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter LinkCategories
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_LinkAccess extends GD_FilterComponent_Metaquery_Post {
		
	function get_filterformcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENT_LINKACCESS;
	}

	function get_formcomponent() {
	
		return GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKACCESS;
	}

	function get_meta_key() {
	
		return GD_METAKEY_POST_LINKACCESS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
global $gd_filtercomponent_linkaccess;
$gd_filtercomponent_linkaccess = new GD_FilterComponent_LinkAccess();