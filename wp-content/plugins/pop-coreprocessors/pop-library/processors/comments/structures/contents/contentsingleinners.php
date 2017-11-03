<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_COMMENTSINGLE', PoP_TemplateIDUtils::get_template_definition('contentinner-commentsingle'));

class GD_Template_Processor_CommentContentInners extends GD_Template_Processor_ContentSingleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_COMMENTSINGLE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_COMMENTSINGLE:

				$ret[] = GD_TEMPLATE_LAYOUT_COMMENT_LIST;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentContentInners();