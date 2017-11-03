<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_USERAVATAR_60', PoP_TemplateIDUtils::get_template_definition('layout-useravatar-60'));
define ('GD_TEMPLATE_LAYOUT_USERAVATAR_60_RESPONSIVE', PoP_TemplateIDUtils::get_template_definition('layout-useravatar-60-responsive'));

class GD_Template_Processor_UserAvatarLayouts extends GD_Template_Processor_UserAvatarLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_USERAVATAR_60,
			GD_TEMPLATE_LAYOUT_USERAVATAR_60_RESPONSIVE,
		);
	}

	function get_avatar($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_USERAVATAR_60:
			case GD_TEMPLATE_LAYOUT_USERAVATAR_60_RESPONSIVE:

				return GD_AVATAR_SIZE_60;
		}

		return parent::get_avatar($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_USERAVATAR_60_RESPONSIVE:

				$this->append_att($template_id, $atts, 'class', 'img-responsive');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserAvatarLayouts();