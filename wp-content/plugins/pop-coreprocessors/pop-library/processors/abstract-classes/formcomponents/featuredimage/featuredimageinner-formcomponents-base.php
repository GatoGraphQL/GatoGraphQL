<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FeaturedImageInnerFormComponentsBase extends GD_Template_Processor_FormComponentsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_FORMCOMPONENT_FEATUREDIMAGE_INNER;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'featuredImageSet', 'set');
		$this->add_jsmethod($ret, 'featuredImageRemove', 'remove');
		return $ret;
	}

	function get_template_path($template_id, $atts) {
	
		return $template_id;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret[GD_JS_TITLES/*'titles'*/] = array(
			'btn-add' => __('Set Featured Image', 'pop-coreprocessors'),
			'btn-remove' => __('Remove', 'pop-coreprocessors'),
			'usernotloggedin' => sprintf(
				__('Please %s to set the featured image.', 'pop-coreprocessors'),
				gd_get_login_html()
			)
		);

		$ret['formcomponent-name'] = $this->get_att($template_id, $atts, 'formcomponent-name');
		$ret['default-img'] = $this->get_att($template_id, $atts, 'default-img');
		$ret[GD_JS_CLASSES/*'classes'*/]['img'] = $this->get_att($template_id, $atts, 'img-class');
		$ret[GD_JS_CLASSES/*'classes'*/]['set-btn'] = $this->get_att($template_id, $atts, 'setbtn-class');
		$ret[GD_JS_CLASSES/*'classes'*/]['remove-btn'] = $this->get_att($template_id, $atts, 'removebtn-class');
		$ret[GD_JS_CLASSES/*'classes'*/]['options'] = $this->get_att($template_id, $atts, 'options-class');
				
		return $ret;
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
					
		$ret[] = array('key' => 'value', 'field' => 'featuredimage');
		$ret[] = array('key' => 'img', 'field' => 'featuredimage-imgsrc');
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'img-class', 'img-responsive');
		$this->add_att($template_id, $atts, 'setbtn-class', 'btn btn-sm btn-primary');
		$this->add_att($template_id, $atts, 'removebtn-class', 'btn btn-sm btn-danger');
		$this->add_att($template_id, $atts, 'options-class', 'options');
		return parent::init_atts($template_id, $atts);
	}
}
