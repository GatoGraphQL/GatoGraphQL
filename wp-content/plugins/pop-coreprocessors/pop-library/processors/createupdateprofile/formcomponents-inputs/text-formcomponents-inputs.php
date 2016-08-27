<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_CUP_SHORTDESCRIPTION', PoP_ServerUtils::get_template_definition('formcomponent-cup-shortdescription'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUP_FACEBOOK', PoP_ServerUtils::get_template_definition('formcomponent-cup-facebook'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUP_TWITTER', PoP_ServerUtils::get_template_definition('formcomponent-cup-twitter'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUP_LINKEDIN', PoP_ServerUtils::get_template_definition('formcomponent-cup-linkedin'));
define ('GD_TEMPLATE_FORMCOMPONENT_CUP_YOUTUBE', PoP_ServerUtils::get_template_definition('formcomponent-cup-youtube'));

class GD_Template_Processor_CreateUpdateProfileTextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_CUP_SHORTDESCRIPTION,
			GD_TEMPLATE_FORMCOMPONENT_CUP_FACEBOOK,
			GD_TEMPLATE_FORMCOMPONENT_CUP_TWITTER,
			GD_TEMPLATE_FORMCOMPONENT_CUP_LINKEDIN,
			GD_TEMPLATE_FORMCOMPONENT_CUP_YOUTUBE
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_SHORTDESCRIPTION:

				return __('Short description', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUP_FACEBOOK:

				// return '<i class="fa fa-fw fa-facebook"></i>'.__('Facebook URL', 'pop-coreprocessors');
				return __('Facebook URL', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUP_TWITTER:

				// return '<i class="fa fa-fw fa-twitter"></i>'.__('Twitter URL', 'pop-coreprocessors');
				return __('Twitter URL', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUP_LINKEDIN:

				// return '<i class="fa fa-fw fa-linkedin"></i>'.__('LinkedIn URL', 'pop-coreprocessors');
				return __('LinkedIn URL', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_CUP_YOUTUBE:

				// return '<i class="fa fa-fw fa-youtube"></i>'.__('Youtube URL', 'pop-coreprocessors');
				return __('Youtube URL', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}
	// function get_placeholder($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_FORMCOMPONENT_CUP_FACEBOOK:

	// 			return 'https://www.facebook.com/...';

	// 		case GD_TEMPLATE_FORMCOMPONENT_CUP_TWITTER:

	// 			return 'https://twitter.com/...';

	// 		case GD_TEMPLATE_FORMCOMPONENT_CUP_LINKEDIN:

	// 			return 'https://www.linkedin.com/...';

	// 		case GD_TEMPLATE_FORMCOMPONENT_CUP_YOUTUBE:

	// 			return 'http://www.youtube.com/...';
	// 	}
		
	// 	return parent::get_placeholder($template_id, $atts);
	// }

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUP_SHORTDESCRIPTION:
				
				$ret[] = array('key' => 'value', 'field' => 'short-description');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_CUP_FACEBOOK:
				
				$ret[] = array('key' => 'value', 'field' => 'facebook');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_CUP_TWITTER:
				
				$ret[] = array('key' => 'value', 'field' => 'twitter');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_CUP_LINKEDIN:
				
				$ret[] = array('key' => 'value', 'field' => 'linkedin');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_CUP_YOUTUBE:
				
				$ret[] = array('key' => 'value', 'field' => 'youtube');
				break;
		}
		
		return $ret;
	}


	function init_atts($template_id, &$atts) {

		// Remove the html code from the placeholder
		$placeholders = array(
			GD_TEMPLATE_FORMCOMPONENT_CUP_FACEBOOK => __('Facebook URL', 'pop-coreprocessors'),
			GD_TEMPLATE_FORMCOMPONENT_CUP_TWITTER => __('Twitter URL', 'pop-coreprocessors'),
			GD_TEMPLATE_FORMCOMPONENT_CUP_LINKEDIN => __('LinkedIn URL', 'pop-coreprocessors'),
			GD_TEMPLATE_FORMCOMPONENT_CUP_YOUTUBE => __('Youtube URL', 'pop-coreprocessors'),
		);
		if ($placeholder = $placeholders[$template_id]) {

			$this->add_att($template_id, $atts, 'placeholder', $placeholder);
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateUpdateProfileTextFormComponentInputs();