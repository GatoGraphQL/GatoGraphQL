<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCES', PoP_ServerUtils::get_template_definition('widgetwrapper-opinionatedvotereferences'));
define ('GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY', PoP_ServerUtils::get_template_definition('widgetwrapper-opinionatedvotereferencedby'));
define ('GD_TEMPLATE_LAZYBUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE', PoP_ServerUtils::get_template_definition('lazypostbuttonwrapper-opinionatedvote-createorupdate'));
define ('GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE', PoP_ServerUtils::get_template_definition('postbuttonwrapper-opinionatedvote-createorcreate'));
define ('GD_TEMPLATE_BUTTONGROUPWRAPPER_OPINIONATEDVOTECOUNT', PoP_ServerUtils::get_template_definition('postbuttongroupwrapper-opinionatedvotecount'));

// Comment Leo 07/12/2015: Use a short name, since it will be added as param "layout" in the url
define ('GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW', PoP_ServerUtils::get_template_definition('opvotereferencedby-fullview', true));
define ('GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS', PoP_ServerUtils::get_template_definition('opvotereferencedby-details', true));
define ('GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE_APPENDTOSCRIPT', PoP_ServerUtils::get_template_definition('opvote-createorupdate', true));

class VotingProcessors_Template_Processor_WidgetWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCES,
			GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY,
			GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
			GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS,
			GD_TEMPLATE_LAZYBUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE,
			GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE_APPENDTOSCRIPT,
			GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE,
			GD_TEMPLATE_BUTTONGROUPWRAPPER_OPINIONATEDVOTECOUNT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCES:

				$ret[] = GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCES;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY:

				$ret[] = GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				$ret[] = GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$ret[] = GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS;
				break;

			case GD_TEMPLATE_LAZYBUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE:

				$ret[] = GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE;
				break;

			case GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE_APPENDTOSCRIPT:

				$ret[] = GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATEORUPDATE_APPENDTOSCRIPT;
				break;

			case GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE:

				$ret[] = GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_UPDATE;
				break;

			case GD_TEMPLATE_BUTTONGROUPWRAPPER_OPINIONATEDVOTECOUNT:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_POSTSTANCE;
				break;
		}

		return $ret;
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE:

				$ret[] = GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATE;
				break;

			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$ret[] = GD_TEMPLATE_LAYOUT_OPINIONATEDVOTEREFERENCEDBYEMPTY_APPENDTOSCRIPT;
				break;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCES:

				$this->append_att($template_id, $atts, 'class', 'references');
				break;
					
			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY:
			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$this->append_att($template_id, $atts, 'class', 'referencedby clearfix');
				break;

			case GD_TEMPLATE_LAZYBUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE:

				$classes = array(
					GD_TEMPLATE_LAZYBUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE => 'createorupdateopinionatedvote',
				);
				$this->add_att($template_id, $atts, 'appendable', true);
				$this->add_att($template_id, $atts, 'appendable-class', $classes[$template_id]);
				break;
		}

		switch ($template_id) {
					
			case GD_TEMPLATE_LAZYBUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE:
			case GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE_APPENDTOSCRIPT:
			case GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE:

				$this->append_att($template_id, $atts, 'class', 'inline');
				break;

			case GD_TEMPLATE_BUTTONGROUPWRAPPER_OPINIONATEDVOTECOUNT:

				$this->append_att($template_id, $atts, 'class', 'pop-stancecount-wrapper');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCES:

				return 'has-references';
					
			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY:
			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGETWRAPPER_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:
			case GD_TEMPLATE_BUTTONGROUPWRAPPER_OPINIONATEDVOTECOUNT:

				return 'has-opinionatedvotereferencedby';

			case GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE:

				return 'has-loggedinuser-opinionatedvotereferencedby';
		}

		return parent::get_condition_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_WidgetWrappers();