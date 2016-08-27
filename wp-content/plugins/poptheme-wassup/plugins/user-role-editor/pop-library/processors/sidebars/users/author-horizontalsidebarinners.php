<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION', PoP_ServerUtils::get_template_definition('horizontal-sidebarinner-author-organization'));
define ('GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL', PoP_ServerUtils::get_template_definition('horizontal-sidebarinner-author-individual'));

class GD_URE_Template_Processor_CustomHorizontalAuthorSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION,
			GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION:

				$ret = array_merge(
					$ret,
					URE_FullUserSidebarSettings::get_components(GD_SIDEBARSECTION_ORGANIZATION)
				);
				break;

			case GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL:

				$ret = array_merge(
					$ret,
					URE_FullUserSidebarSettings::get_components(GD_SIDEBARSECTION_INDIVIDUAL)
				);
				break;
		}

		return $ret;
	}

	function get_wrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION:
			case GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL:

				return 'row';
		}
	
		return parent::get_wrapper_class($template_id);
	}
	
	function get_widgetwrapper_class($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_ORGANIZATION:
			case GD_TEMPLATE_HORIZONTALSIDEBARINNER_AUTHOR_INDIVIDUAL:
			
				return 'col-xsm-4';
		}
	
		return parent::get_widgetwrapper_class($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomHorizontalAuthorSidebarInners();