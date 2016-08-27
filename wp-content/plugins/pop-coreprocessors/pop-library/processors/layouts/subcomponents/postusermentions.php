<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTUSERMENTIONS', PoP_ServerUtils::get_template_definition('layout-postusermentions'));

class GD_Template_Processor_PostUserMentionsLayouts extends GD_Template_Processor_PostUserMentionsLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTUSERMENTIONS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTUSERMENTIONS:

				$ret[] = GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR40;
				break;
		}

		return $ret;
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostUserMentionsLayouts();