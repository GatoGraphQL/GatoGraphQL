<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Organizations
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_ORGANIZATIONS', 'organizations');

class GD_URE_Filter_Organizations extends GD_Filter_Profiles {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_name, $gd_ure_filtercomponent_communities_user, $gd_filtercomponent_orderuser;		
		$ret = array($gd_filtercomponent_name, $gd_ure_filtercomponent_communities_user, $gd_filtercomponent_orderuser);
		$ret = apply_filters('gd_ure_template:filter-organizations:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_ORGANIZATIONS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_organizations = new GD_URE_Filter_Organizations();		
new GD_URE_Filter_Organizations();		
