<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_FORM_FARM_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-farm-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_FARMLINK_RIGHTSIDE', PoP_ServerUtils::get_template_definition('multicomponent-form-farmlink-rightside'));

class OP_Template_Processor_FormMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_FORM_FARM_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_FARMLINK_RIGHTSIDE,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		$status = GD_CreateUpdate_Utils::moderate() ? GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH : GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH;

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_FORM_FARM_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_FARMLINK_RIGHTSIDE:

				$details = array(
					GD_TEMPLATE_MULTICOMPONENT_FORM_FARM_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_FARMDETAILS,
					GD_TEMPLATE_MULTICOMPONENT_FORM_FARMLINK_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_FARMLINKDETAILS,
				);

				$ret[] = $details[$template_id];
				$ret[] = GD_TEMPLATE_WIDGET_FORM_FEATUREDIMAGE;
				$ret[] = GD_TEMPLATE_WIDGET_FORM_METAINFORMATION;
				$ret[] = $status;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_FORM_FARM_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_FARMLINK_RIGHTSIDE:

				if (!($classs = $this->get_general_att($atts, 'formcomponent-publish-class'))) {
					$classs = 'alert alert-info';
				}
				$status = GD_CreateUpdate_Utils::moderate() ? GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH : GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH;
				$this->append_att($status, $atts, 'class', $classs);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_FormMultipleComponents();