<?php
class GD_Template_Helper_GFForm {

	public static function get_contactus_form_field_names() {

		$fieldnames = array(
			'name' => POPTHEME_WASSUP_GF_CONTACTUS_FIELDNAME_NAME_ID,
			'email' => POPTHEME_WASSUP_GF_CONTACTUS_FIELDNAME_EMAIL_ID,
			'topic' => POPTHEME_WASSUP_GF_CONTACTUS_FIELDNAME_TOPIC_ID,
			'subject' => POPTHEME_WASSUP_GF_CONTACTUS_FIELDNAME_SUBJECT_ID,
			'message' => POPTHEME_WASSUP_GF_CONTACTUS_FIELDNAME_MESSAGE_ID
		);
		return apply_filters('gd_gf_contactus_form_fieldnames', $fieldnames);
	}

	public static function get_contactus_form_id() {

		return apply_filters('gd_gf_contactus_form_id', POPTHEME_WASSUP_GF_CONTACTUS_FORM_ID);
	}

	public static function get_contactuser_form_field_names() {

		$fieldnames = array(
			'name' => POPTHEME_WASSUP_GF_CONTACTUSER_FIELDNAME_NAME_ID,
			'email' => POPTHEME_WASSUP_GF_CONTACTUSER_FIELDNAME_EMAIL_ID,
			'subject' => POPTHEME_WASSUP_GF_CONTACTUSER_FIELDNAME_SUBJECT_ID,
			'message' => POPTHEME_WASSUP_GF_CONTACTUSER_FIELDNAME_MESSAGE_ID,
			'pageurl' => POPTHEME_WASSUP_GF_CONTACTUSER_FIELDNAME_PAGEURL_ID,
			'pagetitle' => POPTHEME_WASSUP_GF_CONTACTUSER_FIELDNAME_PAGETITLE_ID
		);
		return apply_filters('gd_gf_contactuser_form_fieldnames', $fieldnames);
	}

	public static function get_contactuser_form_id() {

		return apply_filters('gd_gf_contactuser_form_id', POPTHEME_WASSUP_GF_CONTACTUSER_FORM_ID);
	}

	public static function get_sharebyemail_form_field_names() {

		$fieldnames = array(
			'name' => POPTHEME_WASSUP_GF_SHAREBYEMAIL_FIELDNAME_NAME_ID,
			'destination-emails' => POPTHEME_WASSUP_GF_SHAREBYEMAIL_FIELDNAME_DESTINATIONEMAILS_ID,
			'additional-message' => POPTHEME_WASSUP_GF_SHAREBYEMAIL_FIELDNAME_ADDITIONALMESSAGE_ID,
			'pageurl' => POPTHEME_WASSUP_GF_SHAREBYEMAIL_FIELDNAME_PAGEURL_ID,
			'pagetitle' => POPTHEME_WASSUP_GF_SHAREBYEMAIL_FIELDNAME_PAGETITLE_ID
		);
		return apply_filters('gd_gf_sharebyemail_form_fieldnames', $fieldnames);
	}

	public static function get_sharebyemail_form_id() {

		return apply_filters('gd_gf_sharebyemail_form_id', POPTHEME_WASSUP_GF_SHAREBYEMAIL_FORM_ID);
	}

	public static function get_volunteer_form_field_names() {

		$fieldnames = array(
			'name' => POPTHEME_WASSUP_GF_VOLUNTEER_FIELDNAME_NAME_ID,
			'email' => POPTHEME_WASSUP_GF_VOLUNTEER_FIELDNAME_EMAIL_ID,
			'phone' => POPTHEME_WASSUP_GF_VOLUNTEER_FIELDNAME_PHONE_ID,
			'whyvolunteer' => POPTHEME_WASSUP_GF_VOLUNTEER_FIELDNAME_WHYVOLUNTEER_ID,
			'pageurl' => POPTHEME_WASSUP_GF_VOLUNTEER_FIELDNAME_PAGEURL_ID,
			'pagetitle' => POPTHEME_WASSUP_GF_VOLUNTEER_FIELDNAME_PAGETITLE_ID
		);
		return apply_filters('gd_gf_volunteer_form_fieldnames', $fieldnames);
	}

	public static function get_volunteer_form_id() {

		return apply_filters('gd_gf_volunteer_form_id', POPTHEME_WASSUP_GF_VOLUNTEER_FORM_ID);
	}

	public static function get_flag_form_field_names() {

		$fieldnames = array(
			'name' => POPTHEME_WASSUP_GF_FLAG_FIELDNAME_NAME_ID,
			'email' => POPTHEME_WASSUP_GF_FLAG_FIELDNAME_EMAIL_ID,
			'whyflag' => POPTHEME_WASSUP_GF_FLAG_FIELDNAME_WHYFLAG_ID,
			'pageurl' => POPTHEME_WASSUP_GF_FLAG_FIELDNAME_PAGEURL_ID,
			'pagetitle' => POPTHEME_WASSUP_GF_FLAG_FIELDNAME_PAGETITLE_ID
		);
		return apply_filters('gd_gf_flag_form_fieldnames', $fieldnames);
	}

	public static function get_flag_form_id() {

		return apply_filters('gd_gf_flag_form_id', POPTHEME_WASSUP_GF_FLAG_FORM_ID);
	}

	public static function get_newsletter_form_field_names() {

		$fieldnames =array(
			'email' => POPTHEME_WASSUP_GF_NEWSLETTER_FIELDNAME_EMAIL_ID,
			'name' => POPTHEME_WASSUP_GF_NEWSLETTER_FIELDNAME_NAME_ID,
		);
		return apply_filters('gd_gf_newsletter_form_fieldnames', $fieldnames);
	}

	public static function get_newsletter_form_id() {

		return apply_filters('gd_gf_newsletter_form_id', POPTHEME_WASSUP_GF_NEWSLETTER_FORM_ID);
	}
}
