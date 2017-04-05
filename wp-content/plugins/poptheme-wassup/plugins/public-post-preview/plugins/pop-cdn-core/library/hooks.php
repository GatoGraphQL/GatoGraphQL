<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * CDN Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPThemeWassup_PPP_CDN_Hooks {

	function __construct() {

		add_filter(
			'PoP_CDNCore_Job_ThumbprintsConfig:criteriaitems:rejected:hasParamValues',
			array($this, 'get_rejected_paramvalues')
		);
	}

	function get_rejected_paramvalues($paramvalues) {
		
		// Reject the CDN if viewing a preview post
		$paramvalues[] = array(
			'preview',
			true
		);
		$paramvalues[] = array(
			'preview',
			1
		);
		
		return $paramvalues;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPThemeWassup_PPP_CDN_Hooks();

