<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MIMETYPE', PoP_TemplateIDUtils::get_template_definition('filterformcomponentgroup-mimetype'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TAXONOMY', PoP_TemplateIDUtils::get_template_definition('filterformcomponentgroup-taxonomy'));

class GD_Template_Processor_MediaFormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MIMETYPE,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TAXONOMY,
		);
	}

	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MIMETYPE:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TAXONOMY:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MIMETYPE:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TAXONOMY:

				$ret .= ' col-sm-10';
				break;
		}

		return $ret;
	}

	function get_component($template_id) {

		$components = array(
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MIMETYPE => GD_TEMPLATE_FILTERFORMCOMPONENT_MIMETYPE,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TAXONOMY => GD_TEMPLATE_FILTERFORMCOMPONENT_TAXONOMY,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MediaFormGroups();