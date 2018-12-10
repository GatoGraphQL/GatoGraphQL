<?php
 
define ('GD_DATALOAD_DATASTRUCTURE_DEFAULT', 'default');

class GD_DataLoad_DataStructureFormatter_Default extends GD_DataLoad_DataStructureFormatter {

	function get_name() {
			
		return GD_DATALOAD_DATASTRUCTURE_DEFAULT;
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
$gd_dataload_formatter_default = new GD_DataLoad_DataStructureFormatter_Default();

// Set as the default one
global $gd_dataload_datastructureformat_manager;
$gd_dataload_datastructureformat_manager->set_default($gd_dataload_formatter_default);