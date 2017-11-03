<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLEINNER_MYEVENTS', PoP_TemplateIDUtils::get_template_definition('tableinner-myevents'));
define ('GD_TEMPLATE_TABLEINNER_MYPASTEVENTS', PoP_TemplateIDUtils::get_template_definition('tableinner-mypastevents'));

class GD_EM_Template_Processor_TableInners extends GD_Template_Processor_TableInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLEINNER_MYEVENTS,
			GD_TEMPLATE_TABLEINNER_MYPASTEVENTS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		// Main layout
		switch ($template_id) {

			case GD_TEMPLATE_TABLEINNER_MYEVENTS:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_EDIT;
				$ret[] = GD_TEMPLATE_EM_LAYOUTEVENT_TABLECOL;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;

			case GD_TEMPLATE_TABLEINNER_MYPASTEVENTS:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_PASTEVENT_EDIT;
				$ret[] = GD_TEMPLATE_EM_LAYOUTEVENT_TABLECOL;
				$ret[] = GD_TEMPLATE_LAYOUTPOST_STATUS;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_TableInners();