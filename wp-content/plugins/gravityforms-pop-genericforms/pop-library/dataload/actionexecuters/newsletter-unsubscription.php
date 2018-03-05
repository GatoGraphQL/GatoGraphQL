<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_GF_DATALOAD_ACTIONEXECUTER_NEWSLETTERUNSUBSCRIPTION', 'newsletter-unsubscription');

class GD_GF_DataLoad_ActionExecuter_NewsletterUnsubscription extends GD_DataLoad_ActionExecuter_NewsletterUnsubscription {

    function get_name() {
    
		return GD_GF_DATALOAD_ACTIONEXECUTER_NEWSLETTERUNSUBSCRIPTION;
	}

    protected function get_instance() {

		return new PoP_GF_UnsubscribeFromNewsletter();
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_GF_DataLoad_ActionExecuter_NewsletterUnsubscription();
