<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_INVITENEWMEMBERS', 'invitemembers');

class GD_DataLoad_ActionExecuter_InviteMembers extends GD_DataLoad_ActionExecuter_EmailInvite {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_INVITENEWMEMBERS;
	}

	protected function get_emailinvite_object() {

		global $gd_invitemembers;
		return $gd_invitemembers;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_InviteMembers();