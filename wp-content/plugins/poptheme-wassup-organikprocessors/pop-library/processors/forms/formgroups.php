<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_FARMCATEGORIES', PoP_ServerUtils::get_template_definition('formcomponentgroup-farmcategories'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_FARMCATEGORIES', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-farmcategories'));

class OP_Template_Processor_FormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_FARMCATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_FARMCATEGORIES,
		);
	}


	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_FARMCATEGORIES:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_FARMCATEGORIES:

				$ret .= ' col-sm-10';
				break;
		}

		return $ret;
	}
	
	function get_component($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENTGROUP_FARMCATEGORIES:

				return GD_TEMPLATE_FORMCOMPONENT_FARMCATEGORIES;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_FARMCATEGORIES:

				return GD_TEMPLATE_FILTERFORMCOMPONENT_FARMCATEGORIES;
		}
		
		return parent::get_component($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENTGROUP_FARMCATEGORIES:
			
				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'label', __('Select categories', 'poptheme-wassup-organikprocessors'));
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_FormGroups();