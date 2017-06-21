<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_AUTHORANNOUNCEMENTS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authorannouncements-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHORDISCUSSIONS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authordiscussions-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHORFEATURED_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authorfeatured-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHORLOCATIONPOSTS_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authorlocationposts-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHORSTORIES_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authorstories-sidebar'));

class PoPSP_URE_Template_Processor_SidebarBlockGroups extends GD_Template_Processor_SidebarBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_AUTHORANNOUNCEMENTS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_AUTHORDISCUSSIONS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_AUTHORFEATURED_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_AUTHORLOCATIONPOSTS_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_AUTHORSTORIES_SIDEBAR,
		);
	}

	function get_inner_blocks($template_id) {

		$ret = parent::get_inner_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_AUTHORANNOUNCEMENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORDISCUSSIONS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORFEATURED_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORLOCATIONPOSTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORSTORIES_SIDEBAR:

				$vars = GD_TemplateManager_Utils::get_vars();
				$author = $vars['global-state']['author']/*global $author*/;
				$filters = array(
					GD_TEMPLATE_BLOCKGROUP_AUTHORANNOUNCEMENTS_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHORANNOUNCEMENTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_AUTHORDISCUSSIONS_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHORDISCUSSIONS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_AUTHORFEATURED_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHORFEATURED_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_AUTHORLOCATIONPOSTS_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHORLOCATIONPOSTS_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_AUTHORSTORIES_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHORSTORIES_SIDEBAR,
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

			case GD_TEMPLATE_BLOCKGROUP_AUTHORANNOUNCEMENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORDISCUSSIONS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORFEATURED_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORLOCATIONPOSTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORSTORIES_SIDEBAR:
		
				return POP_SCREEN_AUTHORSECTION;
		}
		
		return parent::get_screen($template_id);
	}

	function get_screengroup($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_AUTHORANNOUNCEMENTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORDISCUSSIONS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORFEATURED_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORLOCATIONPOSTS_SIDEBAR:
			case GD_TEMPLATE_BLOCKGROUP_AUTHORSTORIES_SIDEBAR:

				return POP_SCREENGROUP_CONTENTREAD;
		}
		
		return parent::get_screengroup($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPSP_URE_Template_Processor_SidebarBlockGroups();
