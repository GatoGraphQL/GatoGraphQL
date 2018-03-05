<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postcompactbuttonwrapper-volunteer-big'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbuttonwrapper-volunteer-big'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbuttonwrapper-volunteer-tiny'));

class PoPCore_GenericForms_Template_Processor_ViewComponentButtonWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_POST_VOLUNTEER_BIG;
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_BIG;
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_VOLUNTEER_TINY;
				break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:

				return 'volunteers-needed';
		}

		return parent::get_condition_field($template_id);
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:

				$ret[] = GD_TEMPLATE_HIDEIFEMPTY;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {		

		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:

				$this->append_att($template_id, $atts, 'class', 'volunteer-wrapper');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_GenericForms_Template_Processor_ViewComponentButtonWrappers();