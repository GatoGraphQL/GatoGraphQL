<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_jquery_constants', 'popcore_useraccount_jquery_constants');
function popcore_useraccount_jquery_constants($jquery_constants) {

	// Comment Leo 04/08/2017: the original idea was to allow it to fetch the initial data from many domains
	// However the problem then is that we needed to initialize the loggedin styles for that domain (`wassup_loggedin_styles`)
	// yet, the domain would still not be initialized on the frontend, so when initializing it, it would execute this logic once again,
	// and print the same styles once again. Not nice, so commented it out.
	// // Can fetch many pages, from different domains
	// $loggedinuserdata_urls = GD_Template_Processor_UserAccountUtils::get_loggedinuserdata_urls();
	$loggedinuserdata_urls = array(get_permalink(POP_COREPROCESSORS_PAGE_LOGGEDINUSERDATA));
	$jquery_constants['USERLOGGEDIN_DATA_PAGEURLS'] = $loggedinuserdata_urls;
	
	return $jquery_constants;
}
