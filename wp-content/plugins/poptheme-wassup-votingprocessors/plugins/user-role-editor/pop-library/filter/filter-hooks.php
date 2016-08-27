<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class VotingProcessors_DataLoad_FilterHooks {

    function __construct() {
    
		add_filter(
			'gd_template:filter-opinionatedvotes:filtercomponents',
			array($this, 'filtercomponents')
		);
		add_filter(
			'gd_template:filter-opinionatedvotes-stance:filtercomponents',
			array($this, 'filtercomponents')
		);
	}

	function filtercomponents($filtercomponents) {
		
		global $gd_filtercomponent_search, $gd_ure_filtercomponent_authorrole;
		array_splice($filtercomponents, array_search($gd_filtercomponent_search, $filtercomponents)+1, 0, array($gd_ure_filtercomponent_authorrole));
		return $filtercomponents;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_DataLoad_FilterHooks();