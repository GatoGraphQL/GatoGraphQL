<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_CONTACTPERSON', PoP_ServerUtils::get_template_definition('formcomponentgroup-ure-cup-contactperson'));
define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_CONTACTNUMBER', PoP_ServerUtils::get_template_definition('formcomponentgroup-ure-cup-contactnumber'));
define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_LASTNAME', PoP_ServerUtils::get_template_definition('formcomponentgroup-ure-cup-lastname'));
define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERPRIVILEGES', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-memberprivileges'));
define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERTAGS', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-membertags'));
define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERSTATUS', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-memberstatus'));
define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_ISCOMMUNITY', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-cup-iscommunity'));
// define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_EMAILS', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-emails'));
// define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-additionalmessage'));

define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERPRIVILEGES', PoP_ServerUtils::get_template_definition('ure-filterformcomponentgroup-memberprivileges'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERTAGS', PoP_ServerUtils::get_template_definition('ure-filterformcomponentgroup-membertags'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERSTATUS', PoP_ServerUtils::get_template_definition('ure-filterformcomponentgroup-memberstatus'));

class GD_URE_Template_Processor_ProfileFormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_CONTACTPERSON,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_CONTACTNUMBER,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_LASTNAME,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERPRIVILEGES,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERTAGS,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERSTATUS,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_ISCOMMUNITY,
			// GD_URE_TEMPLATE_FORMCOMPONENTGROUP_EMAILS,
			// GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERPRIVILEGES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERTAGS,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERSTATUS,
		);
	}
	
	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERPRIVILEGES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERTAGS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERSTATUS:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERPRIVILEGES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERTAGS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERSTATUS:

				$ret .= ' col-sm-10';
				break;
		}

		return $ret;
	}

	function get_component($template_id) {

		$components = array(
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_CONTACTPERSON => GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTPERSON,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_CONTACTNUMBER => GD_URE_TEMPLATE_FORMCOMPONENT_CUP_CONTACTNUMBER,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_LASTNAME => GD_URE_TEMPLATE_FORMCOMPONENT_CUP_LASTNAME,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERPRIVILEGES => GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERPRIVILEGES,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERTAGS => GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERTAGS,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERSTATUS => GD_URE_TEMPLATE_FORMCOMPONENT_MEMBERSTATUS,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_ISCOMMUNITY => GD_URE_TEMPLATE_FORMCOMPONENT_CUP_ISCOMMUNITY,
			// GD_URE_TEMPLATE_FORMCOMPONENTGROUP_EMAILS => GD_URE_TEMPLATE_FORMCOMPONENT_EMAILS,
			// GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE => GD_URE_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERPRIVILEGES => GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERPRIVILEGES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERTAGS => GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERTAGS,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_MEMBERSTATUS => GD_URE_TEMPLATE_FILTERFORMCOMPONENT_MEMBERSTATUS,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}

	function get_info($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENTGROUP_CUP_ISCOMMUNITY:
				
				return __('Become a Community: all the content posted by your members will also appear under your Organization\'s profile.');
		
			case GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERSTATUS:
		
				return __('Status "Active" if the user is truly your member, or "Rejected" otherwise. Rejected users will not appear as your Organization\'s members, or contribute content.');

			case GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERPRIVILEGES:
		
				return __('"Contribute content" will add the member\'s content to your profile.', 'ure-popprocessors');

			case GD_URE_TEMPLATE_FORMCOMPONENTGROUP_MEMBERTAGS:
		
				return __('What is the type of relationship from this member to your Organization.', 'ure-popprocessors');
		
			case GD_URE_TEMPLATE_FORMCOMPONENTGROUP_EMAILS:
			
				return __('All the emails below will receive an invitation to join your Organization as members.', 'ure-popprocessors');
		}
		
		return parent::get_info($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_ProfileFormGroups();