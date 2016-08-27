<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GETPOP_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR', PoP_ServerUtils::get_template_definition('getpop-blockgroup-homesection-allcontent-sidebar'));

class GetPoP_Template_Processor_SidebarBlockGroups extends GD_Template_Processor_SidebarBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GETPOP_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR,
		);
	}

	function get_inner_blocks($template_id) {

		$ret = parent::get_inner_blocks($template_id);

		switch ($template_id) {

			case GETPOP_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR:

				// $ret[] = GD_TEMPLATE_BLOCK_BLOG_CAROUSEL;
				$ret[] = GD_TEMPLATE_BLOCK_WEBSITEFEATURES_IDEALFORIMPLEMENTING;
				$ret[] = GD_TEMPLATE_BLOCK_NEWSLETTER;
				break;
		}

		return $ret;
	}

	function get_screen($template_id) {

		$screens = array(
			GETPOP_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR => POP_SCREEN_HOME,
		);
		if ($screen = $screens[$template_id]) {

			return $screen;
		}
		
		return parent::get_screen($template_id);
	}

	function get_screengroup($template_id) {

		switch ($template_id) {

			case GETPOP_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR:

				return POP_SCREENGROUP_CONTENTREAD;
		}
		
		return parent::get_screengroup($template_id);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GETPOP_TEMPLATE_BLOCKGROUP_HOMESECTION_ALLCONTENT_SIDEBAR:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_NEWSLETTER) {

					$this->append_att(GD_TEMPLATE_FORM_NEWSLETTER, $blockgroup_block_atts, 'class', 'alert alert-warning');
				}
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Template_Processor_SidebarBlockGroups();
