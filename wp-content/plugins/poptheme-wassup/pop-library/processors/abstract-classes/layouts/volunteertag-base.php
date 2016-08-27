<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_VolunteerTagLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_VOLUNTEERTAG;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('volunteers-needed');
	}

	function get_title($template_id, $atts) {
	
		return '<i class="fa fa-leaf fa-fw fa-lg"></i>'.__('Volunteer!', 'poptheme-wassup');
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTADDITIONAL_VOLUNTEER:

				$ret[GD_JS_TITLES/*'titles'*/] = array(				
					'volunteer' => $this->get_title($template_id, $atts)
				);				
				break;
		}
		
		return $ret;
	}
}
