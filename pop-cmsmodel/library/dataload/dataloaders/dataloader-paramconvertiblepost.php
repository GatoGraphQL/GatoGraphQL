<?php

define ('GD_DATALOADER_PARAMCONVERTIBLEPOST', 'paramconvertiblepost');
 
class GD_Dataloader_ParamConvertiblePost extends GD_Dataloader_ParamPost {
	
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
new GD_Dataloader_ParamConvertiblePost();