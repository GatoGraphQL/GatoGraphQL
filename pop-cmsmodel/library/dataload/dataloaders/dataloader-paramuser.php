<?php

define ('GD_DATALOADER_PARAMUSER', 'paramuser');
 
class GD_Dataloader_ParamUser extends GD_Dataloader_UserBase {

	use GD_Dataloader_ParamTrait;
	
	function get_name() {
    
		return GD_DATALOADER_PARAMUSER;
	}

	protected function get_param_name() {

		return POP_INPUTNAME_USERID;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_ParamUser();