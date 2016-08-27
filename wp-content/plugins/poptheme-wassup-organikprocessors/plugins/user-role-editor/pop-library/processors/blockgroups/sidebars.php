<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_AUTHORFARMS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authorfarms-sidebar'));

class PoPOP_URE_Template_Processor_SidebarBlockGroups extends GD_Template_Processor_SidebarBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_AUTHORFARMS_SIDEBAR,
		);
	}

	function get_inner_blocks($template_id) {

		$ret = parent::get_inner_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_AUTHORFARMS_SIDEBAR:

				global $author;
				$filters = array(
					GD_TEMPLATE_BLOCKGROUP_AUTHORFARMS_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHORFARMS_SIDEBAR,
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

			case GD_TEMPLATE_BLOCKGROUP_AUTHORFARMS_SIDEBAR:
		
				return POP_SCREEN_AUTHORSECTION;
		}
		
		return parent::get_screen($template_id);
	}

	function get_screengroup($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_AUTHORFARMS_SIDEBAR:

				return POP_SCREENGROUP_CONTENTREAD;
		}
		
		return parent::get_screengroup($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPOP_URE_Template_Processor_SidebarBlockGroups();
