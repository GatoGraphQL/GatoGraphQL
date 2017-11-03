<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL', PoP_TemplateIDUtils::get_template_definition('carouselcontrolbuttongroup-carousel'));

class GD_Template_Processor_CarouselControlButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_TEMPLATE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:

				$ret[] = GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELPREV;
				$ret[] = GD_TEMPLATE_CAROUSELBUTTONCONTROL_CAROUSELNEXT;
				break;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
				
				// Pass the needed atts down the line		
				if ($target = $this->get_att($template_id, $atts, 'carousel-target')) {
						
					foreach ($this->get_modules($template_id) as $module) {
						$this->add_att($module, $atts, 'carousel-target', $target);
					}
				}
				break;
		}

		return parent::init_atts($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_CAROUSELCONTROLBUTTONGROUP_CAROUSEL:
				$this->add_jsmethod($ret, 'carouselControls');
				break;
		}
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CarouselControlButtonGroups();