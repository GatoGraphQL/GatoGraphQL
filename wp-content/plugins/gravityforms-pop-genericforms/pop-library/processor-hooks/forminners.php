<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_GFFormInnerHooks {

	function __construct() {
	
		add_action(
			'GD_Template_Processor_GFFormInners:init-atts',
			array($this, 'init_atts'),
			10,
			3
		);
		add_filter(
			'GD_Template_Processor_GFFormInners:layouts',
			array($this, 'get_layouts'),
			10,
			2
		);
	}

	function get_layouts($layouts, $template_id) {

		$layouts[] = GD_GF_TEMPLATE_FORMCOMPONENT_FORMID;

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_VOLUNTEER:
			case GD_TEMPLATE_FORMINNER_FLAG:

				$layouts[] = GD_TEMPLATE_FORMCOMPONENT_TARGETURL;
				$layouts[] = GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE;
				break;
		}

		return $layouts;
	}

	function init_atts($template_id, $atts_in_array, $processor) {

		$atts = &$atts_in_array[0];
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_CONTACTUS:

				// Form ID
				$form_id = PoP_GFPoPGenericForms_GFHelpers::get_contactus_form_id();
				$processor->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

				// Input names
				$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_contactus_form_field_names();
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts, 'name', $fieldnames['name']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts, 'name', $fieldnames['email']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_TOPIC, $atts, 'name', $fieldnames['topic']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_SUBJECT, $atts, 'name', $fieldnames['subject']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_MESSAGE, $atts, 'name', $fieldnames['message']);
				break;

			case GD_TEMPLATE_FORMINNER_CONTACTUSER:

				// Form ID
				$form_id = PoP_GFPoPGenericForms_GFHelpers::get_contactuser_form_id();
				$processor->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

				// Input names
				$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_contactuser_form_field_names();
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts, 'name', $fieldnames['name']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts, 'name', $fieldnames['email']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_SUBJECT, $atts, 'name', $fieldnames['subject']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_MESSAGE, $atts, 'name', $fieldnames['message']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETURL, $atts, 'name', $fieldnames['pageurl']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE, $atts, 'name', $fieldnames['pagetitle']);
				break;

			case GD_TEMPLATE_FORMINNER_SHAREBYEMAIL:

				// Form ID
				$form_id = PoP_GFPoPGenericForms_GFHelpers::get_sharebyemail_form_id();
				$processor->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

				// Input names
				$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_sharebyemail_form_field_names();
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts, 'name', $fieldnames['name']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_DESTINATIONEMAIL, $atts, 'name', $fieldnames['destination-emails']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE, $atts, 'name', $fieldnames['additional-message']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETURL, $atts, 'name', $fieldnames['pageurl']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE, $atts, 'name', $fieldnames['pagetitle']);
				break;

			case GD_TEMPLATE_FORMINNER_VOLUNTEER:

				// Form ID
				$form_id = PoP_GFPoPGenericForms_GFHelpers::get_volunteer_form_id();
				$processor->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

				// Input names
				$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_volunteer_form_field_names();
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts, 'name', $fieldnames['name']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts, 'name', $fieldnames['email']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_PHONE, $atts, 'name', $fieldnames['phone']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER, $atts, 'name', $fieldnames['whyvolunteer']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETURL, $atts, 'name', $fieldnames['pageurl']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE, $atts, 'name', $fieldnames['pagetitle']);
				break;

			case GD_TEMPLATE_FORMINNER_FLAG:

				// Form ID
				$form_id = PoP_GFPoPGenericForms_GFHelpers::get_flag_form_id();
				$processor->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

				// Input names
				$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_flag_form_field_names();
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts, 'name', $fieldnames['name']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts, 'name', $fieldnames['email']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_WHYFLAG, $atts, 'name', $fieldnames['whyflag']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETURL, $atts, 'name', $fieldnames['pageurl']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE, $atts, 'name', $fieldnames['pagetitle']);
				break;

			case GD_TEMPLATE_FORMINNER_NEWSLETTER:

				// Form ID
				$form_id = PoP_GFPoPGenericForms_GFHelpers::get_newsletter_form_id();
				$processor->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

				// Input names
				$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_newsletter_form_field_names();
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL, $atts, 'name', $fieldnames['email']);
				$processor->add_att(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTERNAME, $atts, 'name', $fieldnames['name']);
				break;
		}
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_GFFormInnerHooks();
