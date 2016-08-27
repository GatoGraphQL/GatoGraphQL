<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL', PoP_ServerUtils::get_template_definition('content-postconclusionsidebar-horizontal'));
define ('GD_TEMPLATE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL', PoP_ServerUtils::get_template_definition('content-subjugatedpostconclusionsidebar-horizontal'));
define ('GD_TEMPLATE_CONTENT_LATESTCOUNTS', PoP_ServerUtils::get_template_definition('content-latestcounts'));

class PoPCore_Template_Processor_Contents extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
			GD_TEMPLATE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL,
			GD_TEMPLATE_CONTENT_LATESTCOUNTS,
		);
	}
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL => GD_TEMPLATE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
			GD_TEMPLATE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL => GD_TEMPLATE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL,
			GD_TEMPLATE_CONTENT_LATESTCOUNTS => GD_TEMPLATE_CONTENTINNER_LATESTCOUNTS,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		$vars = GD_TemplateManager_Utils::get_vars();

		switch ($template_id) {

			case GD_TEMPLATE_CONTENT_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
			case GD_TEMPLATE_CONTENT_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:

				$this->append_att($template_id, $atts, 'class', 'conclusion horizontal sidebar');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_Template_Processor_Contents();