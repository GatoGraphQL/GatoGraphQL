<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoP_CDNCore_Thumbprint_Manager {

    var $thumbprints;
    
    function __construct() {
    
		$this->thumbprints = array();
	}
	
    function add($thumbprint) {
    
		$this->thumbprints[$thumbprint->get_name()] = $thumbprint;		
	}
	
	function get_thumbprints() {

		return array_keys($this->thumbprints);
	}

    function get_thumbprint_value($name) {
        
        $thumbprint = $this->thumbprints[$name];
        if (!$thumbprint) {
        	throw new Exception(sprintf('Error: there is no thumbprint with name \'%s\' (%s)', $name, full_url()));
        }
		$query = $thumbprint->get_query();
        
        // Get the ID for the last modified object
		if ($results = $thumbprint->execute_query($query)) {

		    $object_id = $results[0];

            // The thumbprint is the modification date timestamp
            return (int) $thumbprint->get_timestamp($object_id);
        }

        return '';

		// // Add an id, so that we can easily identify what entity the thumbprint value belongs to
		// // It would normally be the first letter of the name (eg: 'post' => 'p'), but because post and page
		// // both start with 'p', that doesn't work, so if that happens, switch to next letter

		// return $id.$value;
    }
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
global $pop_cdncore_thumbprint_manager;
$pop_cdncore_thumbprint_manager = new PoP_CDNCore_Thumbprint_Manager();
