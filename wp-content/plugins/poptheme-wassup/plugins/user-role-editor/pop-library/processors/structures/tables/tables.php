<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLE_MYMEMBERS', PoP_TemplateIDUtils::get_template_definition('table-mymembers'));

class GD_URE_Template_Processor_Tables extends GD_Template_Processor_TablesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLE_MYMEMBERS,
		);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYMEMBERS:

				$inners = array(
					GD_TEMPLATE_TABLE_MYMEMBERS => GD_TEMPLATE_TABLEINNER_MYMEMBERS,
				);

				return $inners[$template_id];
		}

		return parent::get_inner_template($template_id);
	}

	function get_header_titles($template_id) {

		$ret = parent::get_header_titles($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYMEMBERS:

				$ret[] = __('User', 'poptheme-wassup');
				$ret[] = __('Status', 'poptheme-wassup');
				$ret[] = __('Privileges', 'poptheme-wassup');
				$ret[] = __('Tags', 'poptheme-wassup');
				break;
		}
	
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_Tables();