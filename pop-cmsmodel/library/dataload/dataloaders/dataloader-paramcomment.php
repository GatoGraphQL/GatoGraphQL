<?php

define ('GD_DATALOADER_PARAMCOMMENT', 'paramcomment');
 
class GD_Dataloader_ParamComment extends GD_Dataloader_CommentBase {

	use GD_Dataloader_ParamTrait;

	function get_name() {
    
		return GD_DATALOADER_PARAMCOMMENT;
	}

	protected function get_param_name() {

		return POP_INPUTNAME_COMMENTID;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_ParamComment();