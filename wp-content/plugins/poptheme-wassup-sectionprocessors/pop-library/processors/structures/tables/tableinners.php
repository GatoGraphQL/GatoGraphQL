<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLEINNER_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('tableinner-myannouncements'));
define ('GD_TEMPLATE_TABLEINNER_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('tableinner-mydiscussions'));
define ('GD_TEMPLATE_TABLEINNER_MYPROJECTS', PoP_ServerUtils::get_template_definition('tableinner-myprojects'));
define ('GD_TEMPLATE_TABLEINNER_MYSTORIES', PoP_ServerUtils::get_template_definition('tableinner-mystories'));

class GD_Custom_Template_Processor_TableInners extends GD_Template_Processor_TableInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLEINNER_MYANNOUNCEMENTS,
			GD_TEMPLATE_TABLEINNER_MYDISCUSSIONS,
			GD_TEMPLATE_TABLEINNER_MYPROJECTS,
			GD_TEMPLATE_TABLEINNER_MYSTORIES,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		// Main layout
		switch ($template_id) {

			case GD_TEMPLATE_TABLEINNER_MYANNOUNCEMENTS:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_ANNOUNCEMENT_EDIT;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_DATE;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;

			case GD_TEMPLATE_TABLEINNER_MYDISCUSSIONS:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_DISCUSSION_EDIT;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_DATE;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;

			case GD_TEMPLATE_TABLEINNER_MYPROJECTS:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_PROJECT_EDIT;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_DATE;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;

			case GD_TEMPLATE_TABLEINNER_MYSTORIES:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_STORY_EDIT;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_DATE;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_TableInners();