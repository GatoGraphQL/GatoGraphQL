<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_CDNCore_Hooks {

	function __construct() {

		add_filter(
			'PoP_Engine:etag_header:commoncode',
			array($this, 'get_commoncode')
		);
	}

	function get_commoncode($commoncode) {

		// Remove the thumbprint values from the ETag
		$commoncode = preg_replace('/"'.POP_CDNCORE_THUMBPRINTVALUES.'":{.*?},?/', '', $commoncode);
		return $commoncode;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CDNCore_Hooks();
