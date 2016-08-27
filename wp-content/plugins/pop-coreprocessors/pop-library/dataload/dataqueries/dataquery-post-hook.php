<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class POP_DataQuery_PostHook extends GD_DataQuery_PostHookBase {

	function get_nocachefields() {

		return array(
			'recommendpost-count',
			'recommendpost-count-plus1',
			'upvotepost-count',
			'upvotepost-count-plus1',
			'downvotepost-count',
			'downvotepost-count-plus1',
		);
	}

	function get_lazylayouts() {

		return array(
			'referencedby-lazy|details' => array(
				'default' => GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_DETAILS,
			),
			'referencedby-lazy|simpleview' => array(
				'default' => GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
			),
			'referencedby-lazy|fullview' => array(
				'default' => GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
			),
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new POP_DataQuery_PostHook();