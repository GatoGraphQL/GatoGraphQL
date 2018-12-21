<?php
namespace PoP\Engine\Impl;
 
define ('GD_DATALOAD_DATASTRUCTURE_DEFAULT', 'default');

class DataStructureFormatter_Default extends \PoP\Engine\DataStructureFormatter {

	function get_name() {
			
		return GD_DATALOAD_DATASTRUCTURE_DEFAULT;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
$gd_dataload_formatter_default = new DataStructureFormatter_Default();

// Set as the default one
global $gd_dataload_datastructureformat_manager;
$gd_dataload_datastructureformat_manager->set_default($gd_dataload_formatter_default);