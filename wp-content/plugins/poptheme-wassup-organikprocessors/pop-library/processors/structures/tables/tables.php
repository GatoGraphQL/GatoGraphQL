<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLE_MYFARMS', PoP_ServerUtils::get_template_definition('table-myfarms'));

class OP_Template_Processor_Tables extends GD_Template_Processor_TablesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLE_MYFARMS,
		);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYFARMS:

				$inners = array(
					GD_TEMPLATE_TABLE_MYFARMS => GD_TEMPLATE_TABLEINNER_MYFARMS,
				);

				return $inners[$template_id];
		}

		return parent::get_inner_template($template_id);
	}

	function get_header_titles($template_id) {

		$ret = parent::get_header_titles($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYFARMS:

				$ret[] = __('Farm', 'poptheme-wassup-organikprocessors');
				$ret[] = __('Date', 'poptheme-wassup-organikprocessors');
				$ret[] = __('Status', 'poptheme-wassup-organikprocessors');
				break;
		}
	
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_Tables();