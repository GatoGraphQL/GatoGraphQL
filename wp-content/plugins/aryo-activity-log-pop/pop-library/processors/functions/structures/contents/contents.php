<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENT_MARKNOTIFICATIONASREAD', PoP_ServerUtils::get_template_definition('content-marknotificationasread'));
define ('GD_TEMPLATE_CONTENT_MARKNOTIFICATIONASUNREAD', PoP_ServerUtils::get_template_definition('content-marknotificationasunread'));

class GD_AAL_Template_Processor_FunctionsContents extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENT_MARKNOTIFICATIONASREAD,
			GD_TEMPLATE_CONTENT_MARKNOTIFICATIONASUNREAD,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_CONTENT_MARKNOTIFICATIONASREAD => GD_TEMPLATE_CONTENTINNER_MARKNOTIFICATIONASREAD,
			GD_TEMPLATE_CONTENT_MARKNOTIFICATIONASUNREAD => GD_TEMPLATE_CONTENTINNER_MARKNOTIFICATIONASUNREAD,
		);
		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_FunctionsContents();