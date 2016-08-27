<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR', PoP_ServerUtils::get_template_definition('of-blockgroup-homesection-allcontent-sidebar'));

class OF_Template_Processor_SidebarBlockGroups extends GD_Template_Processor_SidebarBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR,
		);
	}

	function get_inner_blocks($template_id) {

		$ret = parent::get_inner_blocks($template_id);

		switch ($template_id) {

			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR:

				// $ret[] = GD_TEMPLATE_BLOCK_BLOG_CAROUSEL;
				$ret[] = GD_TEMPLATE_BLOCK_NEWSLETTER;
				break;
		}

		return $ret;
	}

	function get_screen($template_id) {

		$screens = array(
			GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR => POP_SCREEN_HOME,
		);
		if ($screen = $screens[$template_id]) {

			return $screen;
		}
		
		return parent::get_screen($template_id);
	}

	function get_screengroup($template_id) {

		switch ($template_id) {

			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR:

				return POP_SCREENGROUP_CONTENTREAD;
		}
		
		return parent::get_screengroup($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OF_Template_Processor_SidebarBlockGroups();
