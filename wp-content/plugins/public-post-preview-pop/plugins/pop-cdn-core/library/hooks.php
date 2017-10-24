<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PPPPoP_CDN_Hooks {

	function __construct() {

		add_filter(
			'PoP_CDNCore_FileReproduction_ThumbprintsConfig:criteriaitems:rejected:hasParamValues',
			array($this, 'get_rejected_paramvalues')
		);
	}

	function get_rejected_paramvalues($paramvalues) {
		
		// Reject the CDN if viewing a preview post
		$paramvalues[] = array(
			POP_PARAMS_PREVIEW,
			true
		);
		$paramvalues[] = array(
			POP_PARAMS_PREVIEW,
			1
		);
		
		return $paramvalues;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PPPPoP_CDN_Hooks();

