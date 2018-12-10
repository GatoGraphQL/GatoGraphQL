<?php

define ('GD_DATALOADER_PARAMPOST', 'parampost');
 
class GD_Dataloader_ParamPost extends GD_Dataloader_PostBase {

	use GD_Dataloader_ParamTrait;
	
	function get_name() {
    
		return GD_DATALOADER_PARAMPOST;
	}

	protected function get_param_name() {

		return POP_INPUTNAME_POSTID;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_ParamPost();