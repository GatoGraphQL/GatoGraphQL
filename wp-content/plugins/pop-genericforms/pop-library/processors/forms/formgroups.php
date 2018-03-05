<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_TOPIC', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-topic'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_NEWSLETTER', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-cup-newsletter'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_NAME', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-name'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-email'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTERNAME', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-newslettername'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAIL', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-newsletteremail'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAILVERIFICATIONCODE', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-newsletteremailverificationcode'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_DESTINATIONEMAIL', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-destinationemail'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_SUBJECT', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-subject'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_PHONE', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-phone'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_MESSAGE', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-message'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-additionalmessage'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_WHYVOLUNTEER', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-whyvolunteer'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_WHYFLAG', PoP_TemplateIDUtils::get_template_definition('gf-formcomponentgroup-field-whyflag'));


class GenericForms_Template_Processor_FormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_TOPIC,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_NEWSLETTER,
			GD_TEMPLATE_FORMCOMPONENTGROUP_NAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL,
			GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTERNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAIL,
			GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAILVERIFICATIONCODE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_DESTINATIONEMAIL,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SUBJECT,
			GD_TEMPLATE_FORMCOMPONENTGROUP_PHONE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_MESSAGE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_WHYVOLUNTEER,
			GD_TEMPLATE_FORMCOMPONENTGROUP_WHYFLAG,
		);
	}

	function get_info($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_NEWSLETTER:
				
				return __('Keep up to date with our community activity through our weekly newsletter.', 'pop-genericforms');
		}
		
		return parent::get_info($template_id, $atts);
	}

	function use_component_configuration($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_NEWSLETTER:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_TOPIC:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_NAME:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTERNAME:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAIL:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAILVERIFICATIONCODE:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_DESTINATIONEMAIL:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_SUBJECT:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_PHONE:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_MESSAGE:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_WHYVOLUNTEER:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_WHYFLAG:

				return false;
		}

		return parent::use_component_configuration($template_id);
	}

	function get_component($template_id) {

		$components = array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_TOPIC => GD_TEMPLATE_FORMCOMPONENT_TOPIC,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_NEWSLETTER => GD_TEMPLATE_FORMCOMPONENT_CUP_NEWSLETTER,
			GD_TEMPLATE_FORMCOMPONENTGROUP_NAME => GD_TEMPLATE_FORMCOMPONENT_NAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL => GD_TEMPLATE_FORMCOMPONENT_EMAIL,
			GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTERNAME => GD_TEMPLATE_FORMCOMPONENT_NEWSLETTERNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAIL => GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL,
			GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAILVERIFICATIONCODE => GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_DESTINATIONEMAIL => GD_TEMPLATE_FORMCOMPONENT_DESTINATIONEMAIL,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SUBJECT => GD_TEMPLATE_FORMCOMPONENT_SUBJECT,
			GD_TEMPLATE_FORMCOMPONENTGROUP_PHONE => GD_TEMPLATE_FORMCOMPONENT_PHONE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_MESSAGE => GD_TEMPLATE_FORMCOMPONENT_MESSAGE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE => GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_WHYVOLUNTEER => GD_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER,
			GD_TEMPLATE_FORMCOMPONENTGROUP_WHYFLAG => GD_TEMPLATE_FORMCOMPONENT_WHYFLAG,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENTGROUP_NAME:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL:

				$this->add_jsmethod($ret, 'addDomainClass');
				break;
		}
		
		return $ret;
	}
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENTGROUP_NAME:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL:

				// For function addDomainClass
				$ret['prefix'] = 'visible-notloggedin-';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_NAME:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL:
				
				$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');

				// If we don't use the loggedinuser-data, then show the inputs always
				if (!PoP_FormUtils::use_loggedinuser_data()) {
					$this->append_att($template_id, $atts, 'class', 'visible-always');
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GenericForms_Template_Processor_FormGroups();