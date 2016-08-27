<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_Custom_Template_Processor_ProfileOrganizationLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_PROFILEORGANIZATION_DETAILS;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('organizationtypes-strings', 'organizationcategories-strings', 'contact-person', 'contact-number');
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret[GD_JS_CLASSES/*'classes'*/]['label'] = 'label-info';
		$ret[GD_JS_CLASSES/*'classes'*/]['label2'] = 'label-primary';
		$ret[GD_JS_TITLES/*'titles'*/] = array(				
			'types' => __('Type', 'poptheme-wassup'),
			'categories' => __('Categories', 'poptheme-wassup'),
			'contactperson' => __('Contact Person', 'poptheme-wassup'),
			'number' => __('Tel / Fax', 'poptheme-wassup'),
		);
		
		return $ret;
	}
}
