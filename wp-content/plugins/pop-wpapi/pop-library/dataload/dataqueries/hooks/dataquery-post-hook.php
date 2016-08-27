<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataQuery_PostHook extends GD_DataQuery_PostHookBase {

	function get_nocachefields() {

		return array('comments-count');
	}

	function get_lazylayouts() {

		return array(
			'comments-lazy' => array(
				'default' => GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT,
			),
			// 'comments-lazy|maxheight' => array(
			// 	'default' => GD_TEMPLATE_MAXHEIGHT_WIDGETWRAPPER_POSTCOMMENTS_APPENDTOSCRIPT,//GD_TEMPLATE_WIDGETWRAPPER_MAXHEIGHTPOSTCOMMENTS_APPENDTOSCRIPT,
			// ),
			'noheadercomments-lazy' => array(
				'default' => GD_TEMPLATE_LAYOUTWRAPPER_COMMENTS_APPENDTOSCRIPT,
			),
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataQuery_PostHook();