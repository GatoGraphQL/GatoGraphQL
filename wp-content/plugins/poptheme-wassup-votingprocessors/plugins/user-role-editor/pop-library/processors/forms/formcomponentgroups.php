<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_AUTHORROLE_MULTISELECT', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-authorrole-multiselect'));

class VotingProcessors_URE_Template_Processor_FormComponentGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_AUTHORROLE_MULTISELECT,
		);
	}

	function get_component($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_AUTHORROLE_MULTISELECT:

				return GD_TEMPLATE_FILTERFORMCOMPONENT_AUTHORROLE_MULTISELECT;
		}
		
		return parent::get_component($template_id);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_AUTHORROLE_MULTISELECT:
		
				return __('By who:', 'poptheme-wassup-votingprocessors');
		}
		
		return parent::get_label($template_id, $atts);
	}

	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_AUTHORROLE_MULTISELECT:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_AUTHORROLE_MULTISELECT:

				$ret .= ' col-sm-10';
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_URE_Template_Processor_FormComponentGroups();