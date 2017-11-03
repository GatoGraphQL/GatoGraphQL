<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-locationpostcategories'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_DISCUSSIONCATEGORIES', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-discussioncategories'));

define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES', PoP_TemplateIDUtils::get_template_definition('filterformcomponentgroup-locationpostcategories'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DISCUSSIONCATEGORIES', PoP_TemplateIDUtils::get_template_definition('filterformcomponentgroup-discussioncategories'));

class GD_Custom_Template_Processor_FormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES,
			GD_TEMPLATE_FORMCOMPONENTGROUP_DISCUSSIONCATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DISCUSSIONCATEGORIES,
		);
	}


	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DISCUSSIONCATEGORIES:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DISCUSSIONCATEGORIES:

				$ret .= ' col-sm-10';
				break;
		}

		return $ret;
	}
	
	function get_component($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES:

				return GD_TEMPLATE_FORMCOMPONENT_LOCATIONPOSTCATEGORIES;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES:

				return GD_TEMPLATE_FILTERFORMCOMPONENT_LOCATIONPOSTCATEGORIES;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_DISCUSSIONCATEGORIES:
			
				return GD_TEMPLATE_FORMCOMPONENT_DISCUSSIONCATEGORIES;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DISCUSSIONCATEGORIES:
			
				return GD_TEMPLATE_FILTERFORMCOMPONENT_DISCUSSIONCATEGORIES;
		}
		
		return parent::get_component($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES:
			// case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LOCATIONPOSTCATEGORIES:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_DISCUSSIONCATEGORIES:
			// case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DISCUSSIONCATEGORIES:
			
				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'label', __('Select categories', 'poptheme-wassup-sectionprocessors'));
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_FormGroups();