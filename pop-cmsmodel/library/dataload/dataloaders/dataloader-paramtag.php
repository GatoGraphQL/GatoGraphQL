<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_PARAMTAG', 'paramtag');
 
class Dataloader_ParamTag extends Dataloader_TagBase {

	use Dataloader_ParamTrait;
	
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
new Dataloader_ParamTag();