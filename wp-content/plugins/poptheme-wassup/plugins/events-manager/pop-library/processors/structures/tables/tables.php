<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TABLE_MYEVENTS', PoP_ServerUtils::get_template_definition('table-myevents'));
define ('GD_TEMPLATE_TABLE_MYPASTEVENTS', PoP_ServerUtils::get_template_definition('table-mypastevents'));

class GD_EM_Template_Processor_Tables extends GD_Template_Processor_TablesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TABLE_MYEVENTS,
			GD_TEMPLATE_TABLE_MYPASTEVENTS,
		);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYEVENTS:
			case GD_TEMPLATE_TABLE_MYPASTEVENTS:

				$inners = array(
					GD_TEMPLATE_TABLE_MYEVENTS => GD_TEMPLATE_TABLEINNER_MYEVENTS,
					GD_TEMPLATE_TABLE_MYPASTEVENTS => GD_TEMPLATE_TABLEINNER_MYPASTEVENTS,
				);

				return $inners[$template_id];
		}

		return parent::get_inner_template($template_id);
	}

	function get_header_titles($template_id) {

		$ret = parent::get_header_titles($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_TABLE_MYEVENTS:

				$ret[] = __('Event', 'poptheme-wassup');
				$ret[] = __('When', 'poptheme-wassup');
				$ret[] = __('Status', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_TABLE_MYPASTEVENTS:

				$ret[] = __('Past Event', 'poptheme-wassup');
				$ret[] = __('When', 'poptheme-wassup');
				$ret[] = __('Status', 'poptheme-wassup');
				break;
		}
	
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_Tables();