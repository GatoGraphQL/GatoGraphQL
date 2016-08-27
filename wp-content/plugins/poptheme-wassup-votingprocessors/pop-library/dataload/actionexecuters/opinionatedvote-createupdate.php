<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_OPINIONATEDVOTE', 'createupdate-opinionatedvote');

class GD_DataLoad_ActionExecuter_CreateUpdate_OpinionatedVoted extends GD_DataLoad_ActionExecuter_CreateUpdate_Post {
// class GD_DataLoad_ActionExecuter_CreateUpdate_OpinionatedVoted extends GD_DataLoad_ActionExecuter_CreateUpdate_UniqueReferenceBase {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATEUPDATE_OPINIONATEDVOTE;
	}

	function get_createupdate() {

		return new GD_CreateUpdate_OpinionatedVoted();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateUpdate_OpinionatedVoted();