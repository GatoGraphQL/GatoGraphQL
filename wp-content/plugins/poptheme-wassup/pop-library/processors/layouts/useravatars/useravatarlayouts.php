<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_USERAVATAR_40', PoP_ServerUtils::get_template_definition('layout-useravatar-40'));
define ('GD_TEMPLATE_LAYOUT_USERAVATAR_40_RESPONSIVE', PoP_ServerUtils::get_template_definition('layout-useravatar-40-responsive'));
define ('GD_TEMPLATE_LAYOUT_USERAVATAR_120', PoP_ServerUtils::get_template_definition('layout-useravatar-120'));
define ('GD_TEMPLATE_LAYOUT_USERAVATAR_120_RESPONSIVE', PoP_ServerUtils::get_template_definition('layout-useravatar-120-responsive'));
define ('GD_TEMPLATE_LAYOUT_USERAVATAR_150', PoP_ServerUtils::get_template_definition('layout-useravatar-150'));
define ('GD_TEMPLATE_LAYOUT_USERAVATAR_150_RESPONSIVE', PoP_ServerUtils::get_template_definition('layout-useravatar-150-responsive'));

class GD_Template_Processor_CustomUserAvatarLayouts extends GD_Template_Processor_UserAvatarLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_LAYOUT_USERAVATAR_16,
			// GD_TEMPLATE_LAYOUT_USERAVATAR_24,
			GD_TEMPLATE_LAYOUT_USERAVATAR_40,
			GD_TEMPLATE_LAYOUT_USERAVATAR_40_RESPONSIVE,
			GD_TEMPLATE_LAYOUT_USERAVATAR_120,
			GD_TEMPLATE_LAYOUT_USERAVATAR_120_RESPONSIVE,
			GD_TEMPLATE_LAYOUT_USERAVATAR_150,
			GD_TEMPLATE_LAYOUT_USERAVATAR_150_RESPONSIVE,
		);
	}

	function get_avatar($template_id) {

		$avatars = array(
			// GD_TEMPLATE_LAYOUT_USERAVATAR_16 => GD_AVATAR_SIZE_16,
			// GD_TEMPLATE_LAYOUT_USERAVATAR_24 => GD_AVATAR_SIZE_24,
			GD_TEMPLATE_LAYOUT_USERAVATAR_40 => GD_AVATAR_SIZE_40,
			GD_TEMPLATE_LAYOUT_USERAVATAR_40_RESPONSIVE => GD_AVATAR_SIZE_40,
			GD_TEMPLATE_LAYOUT_USERAVATAR_120 => GD_AVATAR_SIZE_120,
			GD_TEMPLATE_LAYOUT_USERAVATAR_120_RESPONSIVE => GD_AVATAR_SIZE_120,
			GD_TEMPLATE_LAYOUT_USERAVATAR_150 => GD_AVATAR_SIZE_150,
			GD_TEMPLATE_LAYOUT_USERAVATAR_150_RESPONSIVE => GD_AVATAR_SIZE_150,
		);

		if ($avatar = $avatars[$template_id]) {
			return $avatar;
		}

		return parent::get_avatar($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_USERAVATAR_40_RESPONSIVE:
			case GD_TEMPLATE_LAYOUT_USERAVATAR_120_RESPONSIVE:
			case GD_TEMPLATE_LAYOUT_USERAVATAR_150_RESPONSIVE:

				$this->append_att($template_id, $atts, 'class', 'img-responsive');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomUserAvatarLayouts();