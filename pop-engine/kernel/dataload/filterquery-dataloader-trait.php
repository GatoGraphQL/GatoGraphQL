<?php
namespace PoP\Engine;
 
trait FilterQueryDataloaderTrait {
	
    function filter_query($query, $data_properties) {

    	global $gd_filter_manager;
    	return $gd_filter_manager->filter_query($query, $data_properties);
	}

	function clear_filter() {

		global $gd_filter_manager;
    	$gd_filter_manager->clear_filter();
	}
}
	
