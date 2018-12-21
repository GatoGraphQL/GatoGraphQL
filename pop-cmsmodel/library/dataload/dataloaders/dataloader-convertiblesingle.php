<?php
namespace PoP\CMSModel;
 
define ('GD_DATALOADER_CONVERTIBLESINGLE', 'convertible-single');

class Dataloader_ConvertibleSingle extends Dataloader_PostBase {

	use Dataloader_SingleTrait;

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
new Dataloader_ConvertibleSingle();