<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_CREATELOCATION', 'createlocation');

class GD_DataLoad_ActionExecuter_CreateLocation extends GD_DataLoad_ActionExecuter {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_CREATELOCATION;
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// If the post has been submitted, execute the Gravity Forms shortcode
		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			$EM_Location = new EM_Location();
				
			// Load from $_REQUEST and Validate
			if ( $EM_Location->get_post() && $EM_Location->save() ) { //EM_location gets the location if submitted via POST and validates it (safer than to depend on JS)
				
				// Success!
				// Modify the block-data-settings, saying to select the id of the newly created location
				// $block_data_settings['dataload-atts']['include'] = $EM_Location->post_id;
				$block_execution_bag['dataset'] = array($EM_Location->post_id);

				return array(
					GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
				);
			}
			else{
				
				// $block_execution_bag['data-load'] = false;
				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $EM_Location->get_errors()
				);
			}
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_CreateLocation();