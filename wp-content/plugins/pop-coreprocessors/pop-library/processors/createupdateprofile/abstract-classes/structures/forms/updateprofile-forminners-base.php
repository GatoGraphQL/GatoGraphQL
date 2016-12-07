<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UpdateProfileFormInnersBase extends GD_Template_Processor_UpdateUserFormInnersBase {

	function get_layouts($template_id) {

		$components = parent::get_layouts($template_id);
	
		// Add common Create/Update components
		GD_Template_Processor_CreateUpdateProfileFormsUtils::get_components($template_id, $components, $this);

		// Hook for Newsletter
		$components = apply_filters('gd_template:updateprofile:components', $components, $template_id, $this);
		
		return $components;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUP_DISPLAYEMAIL, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUP_SHORTDESCRIPTION, $atts, 'load-itemobject-value', true);
		// $this->add_att(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUP_FACEBOOK, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUP_TWITTER, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUP_LINKEDIN, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUP_YOUTUBE, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUP_INSTAGRAM, $atts, 'load-itemobject-value', true);
		$this->add_att(GD_TEMPLATE_FORMCOMPONENT_CUP_BLOG, $atts, 'load-itemobject-value', true);

		// Allow to initialize the Locations Map from Events Manager
		$extra_layouts = apply_filters('gd_template:updateprofile:extralayouts', array());
		foreach ($extra_layouts as $extra_layout) {
			$this->add_att($extra_layout, $atts, 'load-itemobject-value', true);
		}

		return parent::init_atts($template_id, $atts);
	}
}