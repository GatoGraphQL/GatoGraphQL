<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTCONTENTINNER_HIGHLIGHTREFERENCEDBY', PoP_TemplateIDUtils::get_template_definition('contentinnerlayout-highlightreferencedby'));
define ('GD_TEMPLATE_LAYOUTCONTENTINNER_HIGHLIGHTREFERENCEDBY_APPENDABLE', PoP_TemplateIDUtils::get_template_definition('contentinnerlayout-highlightreferencedby-appendable'));

class Wassup_Template_Processor_ContentMultipleInners extends GD_Template_Processor_ContentMultipleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTCONTENTINNER_HIGHLIGHTREFERENCEDBY,
			GD_TEMPLATE_LAYOUTCONTENTINNER_HIGHLIGHTREFERENCEDBY_APPENDABLE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUTCONTENTINNER_HIGHLIGHTREFERENCEDBY:

				$ret[] = GD_TEMPLATE_LAYOUT_PREVIEWPOST_HIGHLIGHT_CONTENT;
				break;

			case GD_TEMPLATE_LAYOUTCONTENTINNER_HIGHLIGHTREFERENCEDBY_APPENDABLE:

				// No need for anything, since this is the layout container, to be filled when the lazyload request comes back
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_ContentMultipleInners();