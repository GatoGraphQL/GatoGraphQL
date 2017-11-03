<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EM_TEMPLATE_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS', PoP_TemplateIDUtils::get_template_definition('em-dropdownbuttonquicklink-downloadlinks'));

class GD_EM_Template_Processor_DropdownButtonQuicklinks extends GD_Template_Processor_DropdownButtonControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_EM_TEMPLATE_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS,
		);
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		switch ($template_id) {
		
			case GD_EM_TEMPLATE_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:

				$ret[] = GD_EM_TEMPLATE_BUTTON_GOOGLECALENDAR;
				$ret[] = GD_EM_TEMPLATE_BUTTON_ICAL;
				break;
		}
		
		return $ret;
	}

	function get_btn_class($template_id) {

		switch ($template_id) {
		
			case GD_EM_TEMPLATE_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:

				return 'btn btn-compact btn-link';
		}
		
		return parent::get_btn_class($template_id);
	}

	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_EM_TEMPLATE_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS:

				return 'fa-thumb-tack';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_DropdownButtonQuicklinks();