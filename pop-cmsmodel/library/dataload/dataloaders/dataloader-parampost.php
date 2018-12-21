<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_PARAMPOST', 'parampost');
 
class Dataloader_ParamPost extends Dataloader_PostBase {

	use Dataloader_ParamTrait;
	
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
new Dataloader_ParamPost();