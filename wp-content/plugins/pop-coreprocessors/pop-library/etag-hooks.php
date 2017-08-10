<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Core_EtagHooks {

	function __construct() {

		add_filter(
			'PoP_Engine:etag_header:commoncode',
			array($this, 'get_commoncode')
		);
	}

	function get_commoncode($commoncode) {

		// Remove the thumbprint values from the ETag
		$commoncode = preg_replace('/"'.POP_KEYS_THUMBPRINT.'":[0-9]+,?/', '', $commoncode);
		return $commoncode;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Core_EtagHooks();
