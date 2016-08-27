<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEINDIVIDUAL', 'createupdate-profileindividual');

class GD_DataLoad_ActionExecuter_CreateUpdate_ProfileIndividual extends GD_DataLoad_ActionExecuter_CreateUpdate_Profile {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_PROFILEINDIVIDUAL;
	}

	function get_createupdate() {

		// global $gd_createupdate_profileindividual;
		// return $gd_createupdate_profileindividual;
		return new GD_CreateUpdate_ProfileIndividual();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_ProfileIndividual();