<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLEINNER_MYCONTENT', PoP_TemplateIDUtils::get_template_definition('tableinner-mycontent'));
define ('GD_TEMPLATE_TABLEINNER_MYLINKS', PoP_TemplateIDUtils::get_template_definition('tableinner-mylinks'));
define ('GD_TEMPLATE_TABLEINNER_MYHIGHLIGHTS', PoP_TemplateIDUtils::get_template_definition('tableinner-myhighlights'));
define ('GD_TEMPLATE_TABLEINNER_MYWEBPOSTS', PoP_TemplateIDUtils::get_template_definition('tableinner-mywebposts'));
// define ('GD_TEMPLATE_TABLEINNER_MYRESOURCES', PoP_TemplateIDUtils::get_template_definition('tableinner-myresources'));

class GD_Template_Processor_TableInners extends GD_Template_Processor_TableInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLEINNER_MYCONTENT,
			GD_TEMPLATE_TABLEINNER_MYLINKS,
			GD_TEMPLATE_TABLEINNER_MYHIGHLIGHTS,
			GD_TEMPLATE_TABLEINNER_MYWEBPOSTS,
			// GD_TEMPLATE_TABLEINNER_MYRESOURCES,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		// Main layout
		switch ($template_id) {

			case GD_TEMPLATE_TABLEINNER_MYCONTENT:

				$ret[] = GD_TEMPLATE_LAYOUT_MULTIPLEPOST_EDIT;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;

			case GD_TEMPLATE_TABLEINNER_MYLINKS:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOSTLINK_EDIT;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_DATE;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;

			case GD_TEMPLATE_TABLEINNER_MYHIGHLIGHTS:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_EDIT;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_DATE;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;

			case GD_TEMPLATE_TABLEINNER_MYWEBPOSTS:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_DATE;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;

			// case GD_TEMPLATE_TABLEINNER_MYRESOURCES:

			// 	$ret[] = GD_TEMPLATE_LAYOUTMEDIA_TABLEROW;
			// 	break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TableInners();