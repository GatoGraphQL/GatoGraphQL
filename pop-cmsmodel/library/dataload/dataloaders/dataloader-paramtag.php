<?php

define ('GD_DATALOADER_PARAMTAG', 'paramtag');
 
class GD_Dataloader_ParamTag extends GD_Dataloader_TagBase {

	use GD_Dataloader_ParamTrait;
	
	function get_name() {
    
		return GD_DATALOADER_PARAMTAG;
	}

	protected function get_param_name() {

		return POP_INPUTNAME_TAGID;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_ParamTag();