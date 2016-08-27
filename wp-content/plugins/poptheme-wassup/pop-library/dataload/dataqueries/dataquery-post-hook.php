<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class PoPTheme_Wassup_DataQuery_PostHook extends GD_DataQuery_PostHookBase {

	function get_lazylayouts() {

		return array(
			'highlightreferencedby-lazy|details' => array(
				'default' => GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_DETAILS,
			),
			'highlightreferencedby-lazy|simpleview' => array(
				'default' => GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
			),
			'highlightreferencedby-lazy|fullview' => array(
				'default' => GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
			),
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_DataQuery_PostHook();