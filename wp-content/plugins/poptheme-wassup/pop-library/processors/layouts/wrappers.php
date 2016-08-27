<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION', PoP_ServerUtils::get_template_definition('layoutwrapper-userpostinteraction'));
define ('GD_TEMPLATE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION', PoP_ServerUtils::get_template_definition('layoutwrapper-userhighlightpostinteraction'));
define ('GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER', PoP_ServerUtils::get_template_definition('codewrapper-lazyloadingspinner'));

class GD_Template_Processor_CustomWrapperLayouts extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION,
			GD_TEMPLATE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION,
			GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION:

				$ret[] = GD_TEMPLATE_LAYOUT_USERPOSTINTERACTION;
				break;

			case GD_TEMPLATE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:

				$ret[] = GD_TEMPLATE_LAYOUT_USERHIGHLIGHTPOSTINTERACTION;
				break;

			case GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER:

				$ret[] = GD_TEMPLATE_CODE_LAZYLOADINGSPINNER;
				break;
		}

		return $ret;
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER:

				// This is needed because we need to print the id no matter what, since this template
				// will be referenced using previoustemplates-ids in GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW, etc
				$ret[] = GD_TEMPLATE_CODE_EMPTY;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION:
			case GD_TEMPLATE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:
			case GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER:
			
				return 'published';
		}

		return parent::get_condition_field($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION:
			case GD_TEMPLATE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION:

				$this->append_att($template_id, $atts, 'class', 'userpostinteraction clearfix');
				break;
					
			case GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER:

				$this->append_att($template_id, $atts, 'class', 'loadingmsg clearfix');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomWrapperLayouts();