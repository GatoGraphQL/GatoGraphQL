<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authorevents-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHORPASTEVENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authorpastevents-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTSCALENDAR_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authoreventscalendar-sidebar'));

class PoP_EM_Template_Processor_SidebarBlockGroups extends GD_Template_Processor_SidebarBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_AUTHORPASTEVENTS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTSCALENDAR_SIDEBAR,
		);
	}

	function get_inner_blocks($template_id) {

		$ret = parent::get_inner_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORPASTEVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTSCALENDAR_SIDEBAR:

				global $author;
				$filters = array(
					GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTS_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHOREVENTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_AUTHORPASTEVENTS_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHORPASTEVENTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTSCALENDAR_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHOREVENTSCALENDAR_SIDEBAR,
				);
				$ret[] = $filters[$template_id];

				if (gd_ure_is_organization($author)) {
					
					$ret[] = GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_ORGANIZATION;
				}
				elseif (gd_ure_is_individual($author)) {

					$ret[] = GD_TEMPLATE_BLOCK_AUTHOR_SIDEBAR_INDIVIDUAL;
				}
				break;
		}

		return $ret;
	}

	function get_screen($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORPASTEVENTS_SIDEBAR:
		
				return POP_SCREEN_AUTHORSECTION;

			case GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTSCALENDAR_SIDEBAR:
		
				return POP_SCREEN_AUTHORSECTIONCALENDAR;
		}
		
		return parent::get_screen($template_id);
	}

	function get_screengroup($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORPASTEVENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHOREVENTSCALENDAR_SIDEBAR:

				return POP_SCREENGROUP_CONTENTREAD;
		}
		
		return parent::get_screengroup($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_EM_Template_Processor_SidebarBlockGroups();
