<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * AAL Hook Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_Mentions_Hooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_EditorFormComponentsBase:editor_layouts', 
			array($this, 'add_layouts')
		);
	}

	function add_layouts($layouts) {

		// Add the required layouts to the editor, so it's loaded and available when needed
		$layouts[] = GD_TEMPLATE_LAYOUTUSER_MENTION_COMPONENT;
		$layouts[] = GD_TEMPLATE_LAYOUTTAG_MENTION_COMPONENT;
		return $layouts;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_Mentions_Hooks();
