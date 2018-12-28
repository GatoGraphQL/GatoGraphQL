<?php
namespace PoP\Engine;
 
abstract class QueryDataDataloader extends Dataloader {

    /**
     * Function to override
     */
	function get_dbobject_ids($data_properties) {
	
		return array();
	}
}
	
