<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_PARAMCOMMENT', 'paramcomment');
 
class Dataloader_ParamComment extends Dataloader_CommentBase {

	use Dataloader_ParamTrait;

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
new Dataloader_ParamComment();