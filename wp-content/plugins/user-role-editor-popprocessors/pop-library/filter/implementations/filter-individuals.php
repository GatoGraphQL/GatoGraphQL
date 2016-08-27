<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Individuals
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_INDIVIDUALS', 'individuals');

class GD_URE_Filter_Individuals extends GD_Filter_Profiles {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_name, $gd_ure_filtercomponent_communities_user, $gd_filtercomponent_orderuser;		
		$ret = array($gd_filtercomponent_name, $gd_ure_filtercomponent_communities_user, $gd_filtercomponent_orderuser);
		$ret = apply_filters('gd_ure_template:filter-individuals:filtercomponents', $ret);
		return $ret;
	}
	
	function get_name() {
	
		return GD_FILTER_INDIVIDUALS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_individuals = new GD_URE_Filter_Individuals();		
new GD_URE_Filter_Individuals();		
