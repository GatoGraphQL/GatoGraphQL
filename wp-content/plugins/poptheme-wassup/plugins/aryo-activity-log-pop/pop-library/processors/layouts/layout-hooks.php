<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_AAL_LayoutHooks {

	function __construct() {

		add_filter(
			'GD_AAL_Template_Processor_FunctionsContentMultipleInners:markasread:layouts', 
			array($this, 'markasread_layouts')
		);
		add_filter(
			'GD_AAL_Template_Processor_FunctionsContentMultipleInners:markasunread:layouts', 
			array($this, 'markasunread_layouts')
		);
	}

	function markasread_layouts($layouts) {

		$layouts[] = GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_BGCOLORSTYLES;
		$layouts[] = GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASREAD_TOPBGCOLORSTYLES;
		return $layouts;
	}

	function markasunread_layouts($layouts) {

		$layouts[] = GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_BGCOLORSTYLES;
		$layouts[] = GD_TEMPLATE_LAYOUT_MARKNOTIFICATIONASUNREAD_TOPBGCOLORSTYLES;
		return $layouts;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_AAL_LayoutHooks();
