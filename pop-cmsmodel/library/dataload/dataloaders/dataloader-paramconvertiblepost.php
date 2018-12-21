<?php
namespace PoP\CMSModel;

define ('GD_DATALOADER_PARAMCONVERTIBLEPOST', 'paramconvertiblepost');
 
class Dataloader_ParamConvertiblePost extends Dataloader_ParamPost {
	
	function get_name() {
    
		return GD_DATALOADER_PARAMCONVERTIBLEPOST;
	}

	function get_fieldprocessor() {

		return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new Dataloader_ParamConvertiblePost();