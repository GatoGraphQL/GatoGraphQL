<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postcompactbuttonwrapper-volunteer-big'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbuttonwrapper-volunteer-big'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbuttonwrapper-volunteer-tiny'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbuttonwrapper-addcomment'));
define ('GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG', PoP_TemplateIDUtils::get_template_definition('viewcomponent-postbuttonwrapper-addcomment-big'));
define ('GD_TEMPLATE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL', PoP_TemplateIDUtils::get_template_definition('layoutwrapper-postconclusionsidebar-horizontal'));
define ('GD_TEMPLATE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL', PoP_TemplateIDUtils::get_template_definition('layoutwrapper-subjugatedpostconclusionsidebar-horizontal'));

// define ('GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_USER_SENDMESSAGE_BIG', 'viewcomponent-usercompactbuttonwrapper-sendmessage-big');

class GD_Template_Processor_ViewComponentButtonWrappers extends GD_Template_Processor_ConditionWrapperBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT,
			GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG,
			GD_TEMPLATE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
			GD_TEMPLATE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL,
			// GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_USER_SENDMESSAGE_BIG,
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

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT_BIG;
				break;

			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT:

				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTON_POST_ADDCOMMENT;
				break;

			case GD_TEMPLATE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:

				$ret[] = GD_TEMPLATE_LAYOUT_POSTCONCLUSIONSIDEBAR_HORIZONTAL;
				break;

			case GD_TEMPLATE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:

				$ret[] = GD_TEMPLATE_LAYOUT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL;
				break;

			// case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_USER_SENDMESSAGE_BIG:

			// 	$ret[] = GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTON_USER_SENDMESSAGE_BIG;
			// 	break;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:

				return 'volunteers-needed';
		
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_ADDCOMMENT_BIG:
			case GD_TEMPLATE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
			case GD_TEMPLATE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:
			
				return 'published';
		
			// case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_USER_SENDMESSAGE_BIG:

			// 	return 'is-profile';
		}

		return parent::get_condition_field($template_id);
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG:
			case GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POST_VOLUNTEER_TINY:
			// case GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_USER_SENDMESSAGE_BIG:

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
new GD_Template_Processor_ViewComponentButtonWrappers();