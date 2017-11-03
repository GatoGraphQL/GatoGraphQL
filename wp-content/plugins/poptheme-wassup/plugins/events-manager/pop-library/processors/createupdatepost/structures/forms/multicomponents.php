<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE', PoP_TemplateIDUtils::get_template_definition('multicomponent-form-event-rightside'));
define ('GD_TEMPLATE_MULTICOMPONENT_FORM_EVENTLINK_RIGHTSIDE', PoP_TemplateIDUtils::get_template_definition('multicomponent-form-eventlink-rightside'));

class GD_EM_Custom_Template_Processor_FormMultipleComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE,
			GD_TEMPLATE_MULTICOMPONENT_FORM_EVENTLINK_RIGHTSIDE,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		$status = GD_CreateUpdate_Utils::moderate() ? GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_MODERATEDPUBLISH : GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH;

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_EVENTLINK_RIGHTSIDE:

				$details = array(
					GD_TEMPLATE_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_EVENTDETAILS,
					GD_TEMPLATE_MULTICOMPONENT_FORM_EVENTLINK_RIGHTSIDE => GD_TEMPLATE_WIDGET_FORM_EVENTLINKDETAILS,
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

			case GD_TEMPLATE_MULTICOMPONENT_FORM_EVENT_RIGHTSIDE:
			case GD_TEMPLATE_MULTICOMPONENT_FORM_EVENTLINK_RIGHTSIDE:

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
new GD_EM_Custom_Template_Processor_FormMultipleComponents();