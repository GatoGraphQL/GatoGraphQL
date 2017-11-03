<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_VOLUNTEER', PoP_TemplateIDUtils::get_template_definition('blockgroup-volunteer'));
define ('GD_TEMPLATE_BLOCKGROUP_FLAG', PoP_TemplateIDUtils::get_template_definition('blockgroup-flag'));
define ('GD_TEMPLATE_BLOCKGROUP_CONTACTUSER', PoP_TemplateIDUtils::get_template_definition('blockgroup-contactuser'));

class GD_Template_Processor_GFBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_VOLUNTEER,
			GD_TEMPLATE_BLOCKGROUP_FLAG,
			GD_TEMPLATE_BLOCKGROUP_CONTACTUSER,
		);
	}

	function get_title($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCKGROUP_VOLUNTEER:

				return gd_navigation_menu_item(POPTHEME_WASSUP_GF_PAGE_VOLUNTEER, true).__('Volunteer', 'poptheme-wassup');

			case GD_TEMPLATE_BLOCKGROUP_FLAG:

				return gd_navigation_menu_item(POPTHEME_WASSUP_GF_PAGE_FLAG, true).__('Flag as inappropriate', 'poptheme-wassup');

			case GD_TEMPLATE_BLOCKGROUP_CONTACTUSER:

				return gd_navigation_menu_item(POPTHEME_WASSUP_GF_PAGE_CONTACTUSER, true).__('Send message', 'poptheme-wassup');
		}
		
		return parent::get_title($template_id);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {
		
		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_VOLUNTEER:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_VOLUNTEER) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_POSTHEADER) {

					$description = __('Volunteer for:', 'poptheme-wassup');
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
				}
				break;

			case GD_TEMPLATE_BLOCKGROUP_FLAG:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_FLAG) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_POSTHEADER) {

					$description = __('Flag as inappropriate:', 'poptheme-wassup');
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
				}
				break;

			case GD_TEMPLATE_BLOCKGROUP_CONTACTUSER:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_CONTACTUSER) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_USERHEADER) {

					$description = __('Send a message to:', 'poptheme-wassup');
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
				}
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_VOLUNTEER:

				$ret[] = GD_TEMPLATE_BLOCK_POSTHEADER;
				$ret[] = GD_TEMPLATE_BLOCK_VOLUNTEER;
				break;

			case GD_TEMPLATE_BLOCKGROUP_FLAG:

				$ret[] = GD_TEMPLATE_BLOCK_POSTHEADER;
				$ret[] = GD_TEMPLATE_BLOCK_FLAG;
				break;

			case GD_TEMPLATE_BLOCKGROUP_CONTACTUSER:

				$ret[] = GD_TEMPLATE_BLOCK_USERHEADER;
				$ret[] = GD_TEMPLATE_BLOCK_CONTACTUSER;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_GFBlockGroups();
