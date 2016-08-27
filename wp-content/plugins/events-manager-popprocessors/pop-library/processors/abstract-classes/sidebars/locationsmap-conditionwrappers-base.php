<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_EM_Template_Processor_LocationMapConditionWrappersBase extends GD_Template_Processor_ConditionWrapperBase {

	function get_locationlinks_template($template_id) {
	
		return null;
	}

	function get_map_template($template_id) {
	
		return GD_TEMPLATE_MAP_SIDEBARINDIVIDUAL;
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		if ($locationslinks = $this->get_locationlinks_template($template_id)) {
			$ret[] = $locationslinks;
		}
		if ($map = $this->get_map_template($template_id)) {
			$ret[] = $map;
		}

		return $ret;
	}

	function get_condition_field($template_id) {

		return 'has-locations';
	}

	function get_conditionfailed_layouts($template_id) {

		$ret = parent::get_conditionfailed_layouts($template_id);

		$ret[] = GD_EM_TEMPLATE_MESSAGE_NOLOCATION;

		return $ret;
	}
}