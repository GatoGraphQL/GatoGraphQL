<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_SIDE', PoP_TemplateIDUtils::get_template_definition('blockgroup-side'));

class GD_Template_Processor_SideBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_SIDE
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		$theme = GD_TemplateManager_Utils::get_theme();
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_SIDE:

				// Allow GetPoP to only keep the Sections menu
				if ($layouts = apply_filters(
					'GD_Template_Processor_SideBlockGroups:blocks',
					array(
						GD_TEMPLATE_BLOCK_MENU_SIDE_ADDNEW,
						GD_TEMPLATE_BLOCK_MENU_SIDE_SECTIONS_MULTITARGET,
						GD_TEMPLATE_BLOCK_MENU_SIDE_MYSECTIONS,
					),
					$template_id
				)) {
					$ret = array_merge(
						$ret,
						$layouts
					);
				}
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SideBlockGroups();
