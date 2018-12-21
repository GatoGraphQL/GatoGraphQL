<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_PARAMUSER', 'paramuser');
 
class Dataloader_ParamUser extends Dataloader_UserBase {

	use Dataloader_ParamTrait;
	
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
new Dataloader_ParamUser();