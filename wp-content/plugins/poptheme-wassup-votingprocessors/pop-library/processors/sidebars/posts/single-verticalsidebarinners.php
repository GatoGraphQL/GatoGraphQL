<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-single-opinionatedvote'));

class VotingProcessors_Template_Processor_CustomVerticalSingleSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_OPINIONATEDVOTE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_OPINIONATEDVOTE:

				$ret = array_merge(
					$ret,
					VotingProcessors_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_OPINIONATEDVOTE)
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomVerticalSingleSidebarInners();