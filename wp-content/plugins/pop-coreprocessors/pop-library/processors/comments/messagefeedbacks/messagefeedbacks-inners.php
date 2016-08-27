<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_COMMENTS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-comments'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_ADDCOMMENT', PoP_ServerUtils::get_template_definition('messagefeedbackinner-addcomment'));

class GD_Template_Processor_CommentsMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_COMMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ADDCOMMENT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_COMMENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_COMMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ADDCOMMENT => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ADDCOMMENT,
		);

		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentsMessageFeedbackInners();