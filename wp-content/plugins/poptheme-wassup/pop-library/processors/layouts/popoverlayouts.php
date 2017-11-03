<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POPOVER_USER', PoP_TemplateIDUtils::get_template_definition('layout-popover-user'));
define ('GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR', PoP_TemplateIDUtils::get_template_definition('layout-popover-user-avatar'));
define ('GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR60', PoP_TemplateIDUtils::get_template_definition('layout-popover-user-avatar60'));
define ('GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR40', PoP_TemplateIDUtils::get_template_definition('layout-popover-user-avatar40'));
define ('GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR26', PoP_TemplateIDUtils::get_template_definition('layout-popover-user-avatar26'));

class GD_Template_Processor_CustomPopoverLayouts extends GD_Template_Processor_PopoverLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POPOVER_USER,
			GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR,
			GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR60,
			GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR40,
			GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR26,
		);
	}

	function get_layout($template_id) {

		switch ($template_id) {
						
			case GD_TEMPLATE_LAYOUT_POPOVER_USER:
			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR:
			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR60:
			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR40:
			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR26:

				// return GD_TEMPLATE_LAYOUTUSER;
				return GD_TEMPLATE_LAYOUT_MULTIPLEUSER_POPOVER;
		}
		
		return parent::get_layout($template_id);
	}

	function get_layout_content($template_id) {

		switch ($template_id) {
						
			case GD_TEMPLATE_LAYOUT_POPOVER_USER:

				// return GD_TEMPLATE_LAYOUTPOST_AUTHORNAME_SUMMARY;
				return GD_TEMPLATE_LAYOUTPOST_AUTHORNAME;

			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR120;

			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR60:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR60;

			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR40:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR;

			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR26:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR26;
		}
		
		return parent::get_layout_content($template_id);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POPOVER_USER:
			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR:
			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR60:
			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR40:
			case GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR26:

				// Use no Author popover
				$this->append_att($template_id, $atts, 'class', 'pop-elem');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomPopoverLayouts();