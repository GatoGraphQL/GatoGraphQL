<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_ORGANIZATIONDETAILS', PoP_TemplateIDUtils::get_template_definition('multicomponent-organizationdetails'));

class GD_URE_Template_Processor_LayoutMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_ORGANIZATIONDETAILS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_ORGANIZATIONDETAILS:

				$ret[] = GD_TEMPLATE_LAYOUT_ORGANIZATIONTYPES;
				$ret[] = GD_TEMPLATE_LAYOUT_ORGANIZATIONCATEGORIES;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_ORGANIZATIONDETAILS:

				$modules = $this->get_modules($template_id);
				foreach ($modules as $module) {
					$this->append_att($module, $atts, 'class', 'inline');
				}
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_LayoutMultipleComponents();