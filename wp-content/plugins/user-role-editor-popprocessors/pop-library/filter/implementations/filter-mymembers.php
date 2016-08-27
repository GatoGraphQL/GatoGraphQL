<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Individuals
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_FILTER_MYMEMBERS', 'mymembers');

class GD_URE_Filter_MyMembers extends GD_Filter_Profiles {

	function get_filtercomponents() {
	
		global $gd_filtercomponent_name, $gd_ure_filtercomponent_communities_memberstatus, $gd_ure_filtercomponent_communities_memberprivileges, $gd_ure_filtercomponent_communities_membertags, $gd_filtercomponent_orderuser;		
		return array($gd_filtercomponent_name, $gd_ure_filtercomponent_communities_memberstatus, $gd_ure_filtercomponent_communities_memberprivileges, $gd_ure_filtercomponent_communities_membertags, $gd_filtercomponent_orderuser);
	}
	
	function get_name() {
	
		return GD_FILTER_MYMEMBERS;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// $gd_filter_mymembers = new GD_URE_Filter_MyMembers();		
new GD_URE_Filter_MyMembers();		
