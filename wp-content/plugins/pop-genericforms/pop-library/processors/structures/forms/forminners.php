<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_CONTACTUS', PoP_TemplateIDUtils::get_template_definition('forminner-contactus'));
define ('GD_TEMPLATE_FORMINNER_CONTACTUSER', PoP_TemplateIDUtils::get_template_definition('forminner-contactuser'));
define ('GD_TEMPLATE_FORMINNER_SHAREBYEMAIL', PoP_TemplateIDUtils::get_template_definition('forminner-sharebyemail'));
define ('GD_TEMPLATE_FORMINNER_VOLUNTEER', PoP_TemplateIDUtils::get_template_definition('forminner-volunteer'));
define ('GD_TEMPLATE_FORMINNER_FLAG', PoP_TemplateIDUtils::get_template_definition('forminner-flag'));
define ('GD_TEMPLATE_FORMINNER_NEWSLETTER', PoP_TemplateIDUtils::get_template_definition('forminner-newsletter'));
define ('GD_TEMPLATE_FORMINNER_NEWSLETTERUNSUBSCRIPTION', PoP_TemplateIDUtils::get_template_definition('forminner-newsletterunsubscription'));

class GD_Template_Processor_GFFormInners extends GD_Template_Processor_FormInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMINNER_CONTACTUS,
			GD_TEMPLATE_FORMINNER_CONTACTUSER,
			GD_TEMPLATE_FORMINNER_SHAREBYEMAIL,
			GD_TEMPLATE_FORMINNER_VOLUNTEER,
			GD_TEMPLATE_FORMINNER_FLAG,
			GD_TEMPLATE_FORMINNER_NEWSLETTER,
			GD_TEMPLATE_FORMINNER_NEWSLETTERUNSUBSCRIPTION,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_CONTACTUS:				

				$ret = array_merge(
					$ret,
					array(
						// GD_GF_TEMPLATE_FORMCOMPONENT_FORMID,
						GD_TEMPLATE_FORMCOMPONENTGROUP_NAME,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL,
						// GD_TEMPLATE_FORMCOMPONENTGROUP_TOPIC,
						GD_TEMPLATE_FORMCOMPONENTGROUP_SUBJECT,
						GD_TEMPLATE_FORMCOMPONENTGROUP_MESSAGE,
						GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA,
						GD_GF_TEMPLATE_SUBMITBUTTON_SENDMESSAGE,
					)
				);
				break;

			case GD_TEMPLATE_FORMINNER_CONTACTUSER:

				$ret = array_merge(
					$ret,
					array(
						// GD_GF_TEMPLATE_FORMCOMPONENT_FORMID,
						GD_TEMPLATE_FORMCOMPONENTGROUP_NAME,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL,
						GD_TEMPLATE_FORMCOMPONENTGROUP_SUBJECT,
						GD_TEMPLATE_FORMCOMPONENTGROUP_MESSAGE,
						GD_TEMPLATE_FORMCOMPONENT_USERID,
						GD_TEMPLATE_FORMCOMPONENT_TARGETURL,
						GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE,
						GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA,
						GD_GF_TEMPLATE_SUBMITBUTTON_SENDMESSAGE,
					)
				);
				break;

			case GD_TEMPLATE_FORMINNER_SHAREBYEMAIL:

				$ret = array_merge(
					$ret,
					array(
						// GD_GF_TEMPLATE_FORMCOMPONENT_FORMID,
						GD_TEMPLATE_FORMCOMPONENTGROUP_NAME,
						GD_TEMPLATE_FORMCOMPONENTGROUP_DESTINATIONEMAIL,
						GD_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE,
						GD_TEMPLATE_FORMCOMPONENT_TARGETURL,
						GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE,
						GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA,
						GD_GF_TEMPLATE_SUBMITBUTTON_SENDEMAIL,
					)
				);
				break;

			case GD_TEMPLATE_FORMINNER_VOLUNTEER:

				$ret = array_merge(
					$ret,
					array(
						// GD_GF_TEMPLATE_FORMCOMPONENT_FORMID,
						GD_TEMPLATE_FORMCOMPONENTGROUP_NAME,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL,
						GD_TEMPLATE_FORMCOMPONENTGROUP_PHONE,
						GD_TEMPLATE_FORMCOMPONENTGROUP_WHYVOLUNTEER,
						GD_TEMPLATE_FORMCOMPONENT_POSTID,
						// GD_TEMPLATE_FORMCOMPONENT_TARGETURL,
						// GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE,
						GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA,
						GD_TEMPLATE_SUBMITBUTTON_SUBMIT,
					)
				);
				break;

			case GD_TEMPLATE_FORMINNER_FLAG:

				$ret = array_merge(
					$ret,
					array(
						// GD_GF_TEMPLATE_FORMCOMPONENT_FORMID,
						GD_TEMPLATE_FORMCOMPONENTGROUP_NAME,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EMAIL,
						GD_TEMPLATE_FORMCOMPONENTGROUP_WHYFLAG,
						GD_TEMPLATE_FORMCOMPONENT_POSTID,
						// GD_TEMPLATE_FORMCOMPONENT_TARGETURL,
						// GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE,
						GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA,
						GD_TEMPLATE_SUBMITBUTTON_SUBMIT,
					)
				);
				break;

			case GD_TEMPLATE_FORMINNER_NEWSLETTER:

				$ret = array_merge(
					$ret,
					array(
						// GD_GF_TEMPLATE_FORMCOMPONENT_FORMID,
						GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAIL,
						GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTERNAME,
						GD_GF_TEMPLATE_SUBMITBUTTON_SUBSCRIBE,
					)
				);
				break;

			case GD_TEMPLATE_FORMINNER_NEWSLETTERUNSUBSCRIPTION:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAIL,
						GD_TEMPLATE_FORMCOMPONENTGROUP_NEWSLETTEREMAILVERIFICATIONCODE,
						GD_GF_TEMPLATE_SUBMITBUTTON_CONFIRMUNSUBSCRIPTION,
					)
				);
				break;
		}

		// Allow Gravity Forms to add extra fields
		$ret = apply_filters(
			'GD_Template_Processor_GFFormInners:layouts',
			$ret,
			$template_id
		);

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Allow Gravity Forms to set atts on its added fields
		do_action(
			'GD_Template_Processor_GFFormInners:init-atts', 
			$template_id, 
			array(&$atts), 
			$this
		);

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_NEWSLETTERUNSUBSCRIPTION:

				$inputs = array(
					GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL,
					GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE,
				);
				foreach ($inputs as $input) {
					$this->merge_block_jsmethod_att($input, $atts, array('fillURLParamInput'));
					$this->merge_att($input, $atts, 'params', array(
						'data-urlparam' => $input
					));
				}
				break;

			// case GD_TEMPLATE_FORMINNER_CONTACTUS:

			// 	// // Form ID
			// 	// $form_id = PoP_GFPoPGenericForms_GFHelpers::get_contactus_form_id();
			// 	// $this->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

			// 	// Input names
			// 	$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_contactus_form_field_names();
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts, 'name', $fieldnames['name']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts, 'name', $fieldnames['email']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_TOPIC, $atts, 'name', $fieldnames['topic']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_SUBJECT, $atts, 'name', $fieldnames['subject']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_MESSAGE, $atts, 'name', $fieldnames['message']);
			// 	break;

			// case GD_TEMPLATE_FORMINNER_CONTACTUSER:

			// 	// // Form ID
			// 	// $form_id = PoP_GFPoPGenericForms_GFHelpers::get_contactuser_form_id();
			// 	// $this->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

			// 	// Input names
			// 	$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_contactuser_form_field_names();
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts, 'name', $fieldnames['name']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts, 'name', $fieldnames['email']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_SUBJECT, $atts, 'name', $fieldnames['subject']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_MESSAGE, $atts, 'name', $fieldnames['message']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETURL, $atts, 'name', $fieldnames['pageurl']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE, $atts, 'name', $fieldnames['pagetitle']);
			// 	break;

			// case GD_TEMPLATE_FORMINNER_SHAREBYEMAIL:

			// 	// // Form ID
			// 	// $form_id = PoP_GFPoPGenericForms_GFHelpers::get_sharebyemail_form_id();
			// 	// $this->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

			// 	// Input names
			// 	$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_sharebyemail_form_field_names();
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts, 'name', $fieldnames['name']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_DESTINATIONEMAIL, $atts, 'name', $fieldnames['destination-emails']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE, $atts, 'name', $fieldnames['additional-message']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETURL, $atts, 'name', $fieldnames['pageurl']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE, $atts, 'name', $fieldnames['pagetitle']);
			// 	break;

			// case GD_TEMPLATE_FORMINNER_VOLUNTEER:

			// 	// // Form ID
			// 	// $form_id = PoP_GFPoPGenericForms_GFHelpers::get_volunteer_form_id();
			// 	// $this->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

			// 	// Input names
			// 	$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_volunteer_form_field_names();
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts, 'name', $fieldnames['name']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts, 'name', $fieldnames['email']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_PHONE, $atts, 'name', $fieldnames['phone']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_WHYVOLUNTEER, $atts, 'name', $fieldnames['whyvolunteer']);
			// 	// $this->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETURL, $atts, 'name', $fieldnames['pageurl']);
			// 	// $this->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE, $atts, 'name', $fieldnames['pagetitle']);
			// 	break;

			// case GD_TEMPLATE_FORMINNER_FLAG:

			// 	// // Form ID
			// 	// $form_id = PoP_GFPoPGenericForms_GFHelpers::get_flag_form_id();
			// 	// $this->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

			// 	// Input names
			// 	$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_flag_form_field_names();
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_NAME, $atts, 'name', $fieldnames['name']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_EMAIL, $atts, 'name', $fieldnames['email']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_WHYFLAG, $atts, 'name', $fieldnames['whyflag']);
			// 	// $this->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETURL, $atts, 'name', $fieldnames['pageurl']);
			// 	// $this->add_att(GD_TEMPLATE_FORMCOMPONENT_TARGETTITLE, $atts, 'name', $fieldnames['pagetitle']);
			// 	break;

			// case GD_TEMPLATE_FORMINNER_NEWSLETTER:

			// 	// // Form ID
			// 	// $form_id = PoP_GFPoPGenericForms_GFHelpers::get_newsletter_form_id();
			// 	// $this->add_att(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $atts, 'selected', $form_id);

			// 	// Input names
			// 	$fieldnames = PoP_GFPoPGenericForms_GFHelpers::get_newsletter_form_field_names();
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL, $atts, 'name', $fieldnames['email']);
			// 	$this->add_att(GD_TEMPLATE_FORMCOMPONENT_NEWSLETTERNAME, $atts, 'name', $fieldnames['name']);
			// 	break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_GFFormInners();