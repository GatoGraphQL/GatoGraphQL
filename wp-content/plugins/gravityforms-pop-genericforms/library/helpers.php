<?php
class PoP_GFPoPGenericForms_GFHelpers {

	public static function get_contactus_form_field_names() {

		$fieldnames = array(
			'name' => POP_GENERICFORMS_GF_FORM_CONTACTUS_FIELDNAME_NAME_ID,
			'email' => POP_GENERICFORMS_GF_FORM_CONTACTUS_FIELDNAME_EMAIL_ID,
			'topic' => POP_GENERICFORMS_GF_FORM_CONTACTUS_FIELDNAME_TOPIC_ID,
			'subject' => POP_GENERICFORMS_GF_FORM_CONTACTUS_FIELDNAME_SUBJECT_ID,
			'message' => POP_GENERICFORMS_GF_FORM_CONTACTUS_FIELDNAME_MESSAGE_ID
		);
		return apply_filters('gd_gf_contactus_form_fieldnames', $fieldnames);
	}

	public static function get_contactus_form_id() {

		return apply_filters('gd_gf_contactus_form_id', POP_GENERICFORMS_GF_FORM_CONTACTUS_FORM_ID);
	}

	public static function get_contactuser_form_field_names() {

		$fieldnames = array(
			'name' => POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_NAME_ID,
			'email' => POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_EMAIL_ID,
			'subject' => POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_SUBJECT_ID,
			'message' => POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_MESSAGE_ID,
			'pageurl' => POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_PAGEURL_ID,
			'pagetitle' => POP_GENERICFORMS_GF_FORM_CONTACTUSER_FIELDNAME_PAGETITLE_ID
		);
		return apply_filters('gd_gf_contactuser_form_fieldnames', $fieldnames);
	}

	public static function get_contactuser_form_id() {

		return apply_filters('gd_gf_contactuser_form_id', POP_GENERICFORMS_GF_FORM_CONTACTUSER_FORM_ID);
	}

	public static function get_sharebyemail_form_field_names() {

		$fieldnames = array(
			'name' => POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FIELDNAME_NAME_ID,
			'destination-emails' => POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FIELDNAME_DESTINATIONEMAILS_ID,
			'additional-message' => POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FIELDNAME_ADDITIONALMESSAGE_ID,
			'pageurl' => POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FIELDNAME_PAGEURL_ID,
			'pagetitle' => POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FIELDNAME_PAGETITLE_ID
		);
		return apply_filters('gd_gf_sharebyemail_form_fieldnames', $fieldnames);
	}

	public static function get_sharebyemail_form_id() {

		return apply_filters('gd_gf_sharebyemail_form_id', POP_GENERICFORMS_GF_FORM_SHAREBYEMAIL_FORM_ID);
	}

	public static function get_volunteer_form_field_names() {

		$fieldnames = array(
			'name' => POP_GENERICFORMS_GF_FORM_VOLUNTEER_FIELDNAME_NAME_ID,
			'email' => POP_GENERICFORMS_GF_FORM_VOLUNTEER_FIELDNAME_EMAIL_ID,
			'phone' => POP_GENERICFORMS_GF_FORM_VOLUNTEER_FIELDNAME_PHONE_ID,
			'whyvolunteer' => POP_GENERICFORMS_GF_FORM_VOLUNTEER_FIELDNAME_WHYVOLUNTEER_ID,
			'pageurl' => POP_GENERICFORMS_GF_FORM_VOLUNTEER_FIELDNAME_PAGEURL_ID,
			'pagetitle' => POP_GENERICFORMS_GF_FORM_VOLUNTEER_FIELDNAME_PAGETITLE_ID
		);
		return apply_filters('gd_gf_volunteer_form_fieldnames', $fieldnames);
	}

	public static function get_volunteer_form_id() {

		return apply_filters('gd_gf_volunteer_form_id', POP_GENERICFORMS_GF_FORM_VOLUNTEER_FORM_ID);
	}

	public static function get_flag_form_field_names() {

		$fieldnames = array(
			'name' => POP_GENERICFORMS_GF_FORM_FLAG_FIELDNAME_NAME_ID,
			'email' => POP_GENERICFORMS_GF_FORM_FLAG_FIELDNAME_EMAIL_ID,
			'whyflag' => POP_GENERICFORMS_GF_FORM_FLAG_FIELDNAME_WHYFLAG_ID,
			'pageurl' => POP_GENERICFORMS_GF_FORM_FLAG_FIELDNAME_PAGEURL_ID,
			'pagetitle' => POP_GENERICFORMS_GF_FORM_FLAG_FIELDNAME_PAGETITLE_ID
		);
		return apply_filters('gd_gf_flag_form_fieldnames', $fieldnames);
	}

	public static function get_flag_form_id() {

		return apply_filters('gd_gf_flag_form_id', POP_GENERICFORMS_GF_FORM_FLAG_FORM_ID);
	}

	public static function get_newsletter_form_field_names() {

		$fieldnames =array(
			'email' => POP_GENERICFORMS_GF_FORM_NEWSLETTER_FIELDNAME_EMAIL_ID,
			'name' => POP_GENERICFORMS_GF_FORM_NEWSLETTER_FIELDNAME_NAME_ID,
		);
		return apply_filters('gd_gf_newsletter_form_fieldnames', $fieldnames);
	}

	public static function get_newsletter_form_id() {

		return apply_filters('gd_gf_newsletter_form_id', POP_GENERICFORMS_GF_FORM_NEWSLETTER_FORM_ID);
	}
}
