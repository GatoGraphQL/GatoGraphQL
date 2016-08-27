<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FeaturedImageFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_FEATUREDIMAGE;
	}

	function get_featuredimageinner($template_id) {
	
		return null;
	}

	function get_modules($template_id) {
	
		$featuredimageinner = $this->get_featuredimageinner($template_id);

		return array(
			$featuredimageinner
		);
	}

	function get_image_size($template_id, $atts) {

		return 'thumb-md';
	}
	function get_default_image($template_id, $atts) {

		return POP_COREPROCESSORS_IMAGE_DEFAULTFEATUREDIMAGE;
	}

	function init_atts($template_id, &$atts) {

		$featuredimageinner = $this->get_featuredimageinner($template_id);

		// Transfer the 'load-itemobject-value' value if it has been set
		if ($this->get_att($template_id, $atts, 'load-itemobject-value')) {
			
			$this->add_att($featuredimageinner, $atts, 'load-itemobject-value', true);
		}
		
		$name = $this->get_name($template_id, $atts);
		$this->add_att($featuredimageinner, $atts, 'formcomponent-name', $name);

		// Set the size also to the inner template
		$img_size =  $this->get_image_size($template_id, $atts);
		$this->add_att($featuredimageinner, $atts, 'img-size', $img_size);

		$defaultimg = $this->get_default_image($template_id, $atts);
		$defaultfeatured = wp_get_attachment_image_src($defaultimg, $img_size);
		$defaultfeaturedsrc = array(
			'src' => $defaultfeatured[0],
			'width' => $defaultfeatured[1],
			'height' => $defaultfeatured[2]
		);
		$this->add_att($featuredimageinner, $atts, 'default-img', $defaultfeaturedsrc);

		return parent::init_atts($template_id, $atts);
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$featuredimageinner = $this->get_featuredimageinner($template_id);
		$featuredimageinner_template_processor = $gd_template_processor_manager->get_processor($featuredimageinner);
		$ret['merge-template'] = $featuredimageinner;

		$img_size =  $this->get_image_size($template_id, $atts);
		$ret['img-size'] = $img_size;

		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['featuredimage-inner'] = $featuredimageinner_template_processor->get_settings_id($featuredimageinner);
				
		return $ret;
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		return new GD_FormInput($options);
	}
}
