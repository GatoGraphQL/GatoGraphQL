<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEINDIVIDUAL', 'custom-createupdate-profileindividual');

class GD_Custom_DataLoad_ActionExecuter_CreateUpdate_ProfileIndividual extends GD_DataLoad_ActionExecuter_CreateUpdate_ProfileIndividual {

    function get_name() {
    
		return GD_CUSTOM_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEINDIVIDUAL;
	}

	function get_createupdate() {

		// global $gd_createupdate_profileindividual;
		// return $gd_createupdate_profileindividual;
		return new GD_URE_CreateUpdate_ProfileIndividual();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_DataLoad_ActionExecuter_CreateUpdate_ProfileIndividual();