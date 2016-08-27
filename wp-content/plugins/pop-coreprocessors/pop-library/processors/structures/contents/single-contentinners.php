<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL', PoP_ServerUtils::get_template_definition('contentinner-postconclusionsidebar-horizontal'));
define ('GD_TEMPLATE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL', PoP_ServerUtils::get_template_definition('contentinner-subjugatedpostconclusionsidebar-horizontal'));

class PoPCore_Template_Processor_SingleContentInners extends GD_Template_Processor_ContentSingleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
			GD_TEMPLATE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL,
		);
	}

	protected function get_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:

				return GD_TEMPLATE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL;

			case GD_TEMPLATE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:

				return GD_TEMPLATE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL;
		}

		return null;
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
			case GD_TEMPLATE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:

				$ret[] = $this->get_layout($template_id);
				break;

			// case GD_TEMPLATE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:

			// 	$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL;
			// 	break;

			// case GD_TEMPLATE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:

			// 	$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL;
			// 	break;
		}

		return $ret;
	}

	// function init_atts($template_id, &$atts) {

	// 	$vars = GD_TemplateManager_Utils::get_vars();

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_CONTENTINNER_POSTCONCLUSIONSIDEBAR_HORIZONTAL:
	// 		case GD_TEMPLATE_CONTENTINNER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL:

	// 			// Needed for painting the div in blue in its entirety
	// 			$layout = $this->get_layout($template_id);
	// 			$this->append_att($layout, $atts, 'class', 'col-sm-12');
	// 			$this->append_att($template_id, $atts, 'class', 'row');
	// 			break;
	// 	}
		
	// 	return parent::init_atts($template_id, $atts);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPCore_Template_Processor_SingleContentInners();