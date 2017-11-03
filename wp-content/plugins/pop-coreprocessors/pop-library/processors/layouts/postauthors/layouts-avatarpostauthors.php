<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR', PoP_TemplateIDUtils::get_template_definition('layoutpost-authoravatar'));
define ('GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR26', PoP_TemplateIDUtils::get_template_definition('layoutpost-authoravatar26'));
define ('GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR60', PoP_TemplateIDUtils::get_template_definition('layoutpost-authoravatar60'));
define ('GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR82', PoP_TemplateIDUtils::get_template_definition('layoutpost-authoravatar82'));
define ('GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR120', PoP_TemplateIDUtils::get_template_definition('layoutpost-authoravatar120'));

class GD_Template_Processor_PostAuthorAvatarLayouts extends GD_Template_Processor_PostAuthorAvatarLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR,
			GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR26,
			GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR60,
			GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR82,
			GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR120,
		);
	}

	function get_avatar_size($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR:

				return GD_AVATAR_SIZE_40;

			case GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR26:

				return GD_AVATAR_SIZE_26;

			case GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR60:

				return GD_AVATAR_SIZE_60;

			case GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR82:

				return GD_AVATAR_SIZE_82;

			case GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR120:

				return GD_AVATAR_SIZE_120;
		}
		
		return parent::get_avatar_size($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostAuthorAvatarLayouts();