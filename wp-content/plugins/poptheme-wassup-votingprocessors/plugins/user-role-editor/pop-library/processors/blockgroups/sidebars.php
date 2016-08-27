<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authoropinionatedvotes-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_PRO_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authoropinionatedvotes-pro-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_NEUTRAL_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authoropinionatedvotes-neutral-sidebar'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_AGAINST_SIDEBAR', PoP_ServerUtils::get_template_definition('blockgroup-authoropinionatedvotes-against-sidebar'));

class PoPVP_URE_Template_Processor_SidebarBlockGroups extends GD_Template_Processor_SidebarBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_PRO_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_NEUTRAL_SIDEBAR,
			GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_AGAINST_SIDEBAR,
		);
	}

	function get_inner_blocks($template_id) {

		$ret = parent::get_inner_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_AUTHORFARMS_SIDEBAR:

				global $author;
				$filters = array(
					GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHOROPINIONATEDVOTES_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_PRO_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHOROPINIONATEDVOTES_STANCE_SIDEBAR, //GD_TEMPLATE_BLOCK_AUTHOROPINIONATEDVOTES_PRO_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_NEUTRAL_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHOROPINIONATEDVOTES_STANCE_SIDEBAR, //GD_TEMPLATE_BLOCK_AUTHOROPINIONATEDVOTES_NEUTRAL_SIDEBAR,
					GD_TEMPLATE_BLOCKGROUP_AUTHOROPINIONATEDVOTES_AGAINST_SIDEBAR => GD_TEMPLATE_BLOCK_AUTHOROPINIONATEDVOTES_STANCE_SIDEBAR, //GD_TEMPLATE_BLOCK_AUTHOROPINIONATEDVOTES_AGAINST_SIDEBAR,
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
new PoPVP_URE_Template_Processor_SidebarBlockGroups();
