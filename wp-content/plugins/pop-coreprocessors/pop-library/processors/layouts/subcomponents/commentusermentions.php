<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_COMMENTUSERMENTIONS', PoP_TemplateIDUtils::get_template_definition('layout-commentusermentions'));

class GD_Template_Processor_CommentUserMentionsLayouts extends GD_Template_Processor_CommentUserMentionsLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_COMMENTUSERMENTIONS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_COMMENTUSERMENTIONS:

				$ret[] = GD_TEMPLATE_LAYOUT_POPOVER_USER_AVATAR40;
				break;
		}

		return $ret;
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentUserMentionsLayouts();