<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCES', PoP_ServerUtils::get_template_definition('widget-opinionatedvotereferences'));
define ('GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY', PoP_ServerUtils::get_template_definition('widget-opinionatedvotereferencedby'));
define ('GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW', PoP_ServerUtils::get_template_definition('widget-opinionatedvotereferencedby-appendtoscript-fullview'));
define ('GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS', PoP_ServerUtils::get_template_definition('widget-opinionatedvotereferencedby-appendtoscript-details'));

class VotingProcessors_Template_Processor_Widgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCES,
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY,
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCES:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCES_ADDONS;
				break;

			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY:

				$ret[] = GD_TEMPLATE_SUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY;
				break;

			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				$ret[] = GD_TEMPLATE_LAYOUT_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT;
				break;
		}
		
		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$opinionatedvotes = gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES, 'plural');//__('Thoughts on TPP', 'poptheme-wassup-votingprocessors');
		$titles = array(
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCES => sprintf(
				__('%s after reading...', 'poptheme-wassup-votingprocessors'),
				gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES)//__('Thought', 'poptheme-wassup-votingprocessors'),
			),
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY => $opinionatedvotes,
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW => $opinionatedvotes,
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS => $opinionatedvotes,
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$fontawesomes = array(
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCES => 'fa-asterisk',
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY => 'fa-commenting-o',
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW => 'fa-commenting-o',
			GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS => 'fa-commenting-o',
		);

		return $fontawesomes[$template_id];
	}
	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY:
			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY:
			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY:
			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_widget_class($template_id, $atts);
	}
	function get_title_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY:
			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_title_wrapper($template_id, $atts);
	}
	function get_title_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY:
			// case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			// case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return '';
		}

		return parent::get_title_class($template_id, $atts);
	}
	function get_quicklinkgroup($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY:
			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:

				return GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATE;
		}

		return parent::get_quicklinkgroup($template_id);
	}
	function collapsible($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT_DETAILS:

				return true;
		}

		return parent::collapsible($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_Widgets();