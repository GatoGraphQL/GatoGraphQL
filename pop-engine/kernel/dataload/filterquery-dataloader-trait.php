<?php
namespace PoP\Engine;
 
trait FilterQueryDataloaderTrait {
	
    function filter_query($query, $data_properties) {

    	global $POP_FILTER_manager;
    	return $POP_FILTER_manager->filter_query($query, $data_properties);
	}

	function clear_filter() {

		global $POP_FILTER_manager;
    	$POP_FILTER_manager->clear_filter();
	}
}
	
