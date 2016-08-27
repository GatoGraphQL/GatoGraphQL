<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class POPTheme_VotingProcessors_DataQuery_PostHook extends GD_DataQuery_PostHookBase {

	function get_loggedinuserfields() {

		return array(
			'loggedinuser-opinionatedvotereferencedby',
			'has-loggedinuser-opinionatedvotereferencedby',
			'editopinionatedvote-url',
		);
	}

	function get_lazylayouts() {

		return array(
			'opinionatedvotereferencedby-lazy|details' => array(
				'default' => GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS,
			),
			// 'opinionatedvotereferencedby-lazy|simpleview' => array(
			// 	'default' => GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
			// ),
			'opinionatedvotereferencedby-lazy|fullview' => array(
				'default' => GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
			),
			'createopinionatedvotebutton-lazy' => array(
				'default' => GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE_APPENDTOSCRIPT,
			),
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new POPTheme_VotingProcessors_DataQuery_PostHook();