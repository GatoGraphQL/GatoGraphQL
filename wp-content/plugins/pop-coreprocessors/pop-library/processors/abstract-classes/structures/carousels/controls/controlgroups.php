<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELCONTROLGROUP_CAROUSEL', PoP_TemplateIDUtils::get_template_definition('carouselcontrolgroup-carousel'));

class GD_Template_Processor_CarouselControlGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELCONTROLGROUP_CAROUSEL
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLGROUP_CAROUSEL:
			
				$ret[] = GD_TEMPLATE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Pass the needed control-target atts down the line		
		if ($target = $this->get_att($template_id, $atts, 'carousel-target')) {
				
			foreach ($this->get_modules($template_id) as $module) {
				$this->add_att($module, $atts, 'carousel-target', $target);
			}
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CarouselControlGroups();