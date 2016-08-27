<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_TOPIC', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-topic'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_CUP_NEWSLETTER', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-cup-newsletter'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NAME', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-name'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_EMAIL', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-email'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTERNAME', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-newslettername'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAIL', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-newsletteremail'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_DESTINATIONEMAIL', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-destinationemail'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_SUBJECT', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-subject'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_PHONE', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-phone'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_MESSAGE', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-message'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-additionalmessage'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_WHYVOLUNTEER', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-whyvolunteer'));
define ('GD_GF_TEMPLATE_FORMCOMPONENTGROUP_WHYFLAG', PoP_ServerUtils::get_template_definition('gf-formcomponentgroup-field-whyflag'));


class GD_GF_Template_Processor_FormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_TOPIC,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_CUP_NEWSLETTER,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NAME,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_EMAIL,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTERNAME,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAIL,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_DESTINATIONEMAIL,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_SUBJECT,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_PHONE,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_MESSAGE,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_WHYVOLUNTEER,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_WHYFLAG,
		);
	}

	function get_info($template_id, $atts) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_CUP_NEWSLETTER:
				
				return __('Keep up to date with our community activity through our weekly newsletter.', 'poptheme-wassup');
		}
		
		return parent::get_info($template_id, $atts);
	}

	function use_component_configuration($template_id) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_CUP_NEWSLETTER:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_TOPIC:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NAME:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_EMAIL:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTERNAME:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAIL:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_DESTINATIONEMAIL:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_SUBJECT:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_PHONE:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_MESSAGE:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_WHYVOLUNTEER:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_WHYFLAG:

				return false;
		}

		return parent::use_component_configuration($template_id);
	}

	function get_component($template_id) {

		$components = array(
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_TOPIC => GD_GF_TEMPLATE_FORMCOMPONENT_TOPIC,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_CUP_NEWSLETTER => GD_GF_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NAME => GD_GF_TEMPLATE_FORMCOMPONENT_NAME,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_EMAIL => GD_GF_TEMPLATE_FORMCOMPONENT_EMAIL,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTERNAME => GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTERNAME,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAIL => GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_DESTINATIONEMAIL => GD_GF_TEMPLATE_FORMCOMPONENT_DESTINATIONEMAIL,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_SUBJECT => GD_GF_TEMPLATE_FORMCOMPONENT_SUBJECT,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_PHONE => GD_GF_TEMPLATE_FORMCOMPONENT_PHONE,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_MESSAGE => GD_GF_TEMPLATE_FORMCOMPONENT_MESSAGE,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE => GD_GF_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_WHYVOLUNTEER => GD_GF_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER,
			GD_GF_TEMPLATE_FORMCOMPONENTGROUP_WHYFLAG => GD_GF_TEMPLATE_FORMCOMPONENT_WHYFLAG,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_NAME:
			case GD_GF_TEMPLATE_FORMCOMPONENTGROUP_EMAIL:
				
				$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_GF_Template_Processor_FormGroups();