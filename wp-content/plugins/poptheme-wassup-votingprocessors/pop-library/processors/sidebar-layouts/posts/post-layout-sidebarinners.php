<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-vertical-opinionatedvote'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-horizontal-opinionatedvote'));
define ('GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('layout-postsidebarinner-compacthorizontal-opinionatedvote'));

class VotingProcessors_Template_Processor_CustomPostLayoutSidebarInners extends GD_Template_Processor_SidebarInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_OPINIONATEDVOTE,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_OPINIONATEDVOTE,
			GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_OPINIONATEDVOTE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_VERTICAL_OPINIONATEDVOTE:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_OPINIONATEDVOTE:

				$ret = array_merge(
					$ret,
					VotingProcessors_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_OPINIONATEDVOTE)
				);
				break;

			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_OPINIONATEDVOTE:

				$ret = array_merge(
					$ret,
					VotingProcessors_FullViewSidebarSettings::get_components(GD_COMPACTSIDEBARSECTION_OPINIONATEDVOTE)
				);
				break;

		}
		
		return $ret;
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_OPINIONATEDVOTE:
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_OPINIONATEDVOTE:

				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}
	
	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_HORIZONTAL_OPINIONATEDVOTE:
			
				return 'col-xsm-4';
			
			case GD_TEMPLATE_LAYOUT_POSTSIDEBARINNER_COMPACTHORIZONTAL_OPINIONATEDVOTE:
			
				return 'col-xsm-6';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomPostLayoutSidebarInners();