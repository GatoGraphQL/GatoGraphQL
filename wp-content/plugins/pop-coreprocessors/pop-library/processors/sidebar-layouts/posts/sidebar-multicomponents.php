<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT', PoP_TemplateIDUtils::get_template_definition('postconclusion-sidebarmulticomponent-left'));
define ('GD_TEMPLATE_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT', PoP_TemplateIDUtils::get_template_definition('subjugatedpostconclusion-sidebarmulticomponent-left'));
define ('GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT', PoP_TemplateIDUtils::get_template_definition('postconclusion-sidebarmulticomponent-right'));

class GD_Template_Processor_PostMultipleSidebarComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT,
			GD_TEMPLATE_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT,
			GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT:

				$ret[] = GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
				break;

			case GD_TEMPLATE_SUBJUGATEDPOSTCONCLUSIONSIDEBARMULTICOMPONENT_LEFT:

				$ret[] = GD_TEMPLATE_SUBJUGATEDPOSTSOCIALMEDIA_POSTWRAPPER;
				break;

			case GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT:

				$ret[] = GD_TEMPLATE_LAYOUT_SIMPLEPOSTAUTHORS;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_POSTCONCLUSIONSIDEBARMULTICOMPONENT_RIGHT:

				$this->append_att(GD_TEMPLATE_LAYOUT_SIMPLEPOSTAUTHORS, $atts, 'class', 'pull-right');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostMultipleSidebarComponents();