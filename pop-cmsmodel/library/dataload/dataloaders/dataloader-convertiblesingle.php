<?php
 
define ('GD_DATALOADER_CONVERTIBLESINGLE', 'convertible-single');

class GD_Dataloader_ConvertibleSingle extends GD_Dataloader_PostBase {

	use GD_Dataloader_SingleTrait;

	function get_name() {
    
		return GD_DATALOADER_CONVERTIBLESINGLE;
	}

    function get_fieldprocessor() {

		return GD_DATALOAD_CONVERTIBLEFIELDPROCESSOR_POSTS;
	}
}
	

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Dataloader_ConvertibleSingle();