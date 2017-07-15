<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_DATALOAD_ACTIONEXECUTER_NEWSLETTERUNSUBSCRIPTION', 'newsletter-unsubscription');

class GD_DataLoad_ActionExecuter_NewsletterUnsubscription extends GD_DataLoad_ActionExecuter {

    function get_name() {
    
		return GD_DATALOAD_ACTIONEXECUTER_NEWSLETTERUNSUBSCRIPTION;
	}

    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

		// If the post has been submitted, execute the Gravity Forms shortcode
		if ('POST' == $_SERVER['REQUEST_METHOD']) {

			$errors = array();
			$pop_unsubscribe_from_newsletter = new PoP_UnsubscribeFromNewsletter();
			$pop_unsubscribe_from_newsletter->unsubscribe($errors, $block_atts);

			if ($errors) {

				return array(
					GD_DATALOAD_IOHANDLER_FORM_ERRORSTRINGS => $errors
				);
			}
			
			// No errors => success
			return array(
				GD_DATALOAD_IOHANDLER_FORM_SUCCESS => true
			);	
		}

		return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_NewsletterUnsubscription();