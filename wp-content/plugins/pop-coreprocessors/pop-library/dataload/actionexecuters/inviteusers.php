<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_INVITENEWUSERS', 'inviteusers');

class GD_DataLoad_ActionExecuter_InviteUsers extends GD_DataLoad_ActionExecuter_EmailInvite {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_INVITENEWUSERS;
	}

	protected function get_emailinvite_object() {

		global $gd_inviteusers;
		return $gd_inviteusers;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_InviteUsers();