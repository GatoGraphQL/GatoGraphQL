<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLEINNER_MYMEMBERS', PoP_TemplateIDUtils::get_template_definition('tableinner-mymembers'));

class GD_URE_Template_Processor_TableInners extends GD_Template_Processor_TableInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLEINNER_MYMEMBERS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		// Main layout
		switch ($template_id) {

			case GD_TEMPLATE_TABLEINNER_MYMEMBERS:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWUSER_PROFILE_EDITMEMBERS;
				$ret[] = GD_URE_TEMPLATE_LAYOUTUSER_MEMBERSTATUS;
				$ret[] = GD_URE_TEMPLATE_LAYOUTUSER_MEMBERPRIVILEGES;
				$ret[] = GD_URE_TEMPLATE_LAYOUTUSER_MEMBERTAGS;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_TableInners();