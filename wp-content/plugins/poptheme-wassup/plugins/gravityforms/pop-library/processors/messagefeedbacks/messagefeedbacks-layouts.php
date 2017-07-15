<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUS', PoP_ServerUtils::get_template_definition('layout-messagefeedback-contactus'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUSER', PoP_ServerUtils::get_template_definition('layout-messagefeedback-contactuser'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SHAREBYEMAIL', PoP_ServerUtils::get_template_definition('layout-messagefeedback-sharebyemail'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_VOLUNTEER', PoP_ServerUtils::get_template_definition('layout-messagefeedback-volunteer'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FLAG', PoP_ServerUtils::get_template_definition('layout-messagefeedback-flag'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTER', PoP_ServerUtils::get_template_definition('layout-messagefeedback-newsletter'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTERUNSUBSCRIPTION', PoP_ServerUtils::get_template_definition('layout-messagefeedback-newsletterunsubscription'));

class GD_GF_Template_Processor_MessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUSER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SHAREBYEMAIL,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_VOLUNTEER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FLAG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTERUNSUBSCRIPTION,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		$ret['empty-name'] = __('Name is missing.', 'poptheme-wassup');
		$ret['empty-email'] = __('Email is missing or format is incorrect.', 'poptheme-wassup');
		$ret['empty-message'] = __('Message is missing.', 'poptheme-wassup');

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUS:

				$ret['success-header'] = __('Message sent successfully!', 'poptheme-wassup');
				$ret['success'] = __('Thanks for contacting us, we will get in touch with you shortly.', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUSER:

				$ret['success-header'] = __('Message sent successfully!', 'poptheme-wassup');
				// $ret['success'] = __("Let's keep connecting the green dots.", 'poptheme-wassup');
				$ret['success'] = __("Your message has been sent to the user by email.", 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SHAREBYEMAIL:

				$ret['success-header'] = __('Email sent successfully.', 'poptheme-wassup');
				$ret['success'] = __("Any more friends who might be interested? Keep sending!", 'poptheme-wassup');
				$ret['empty-destination-email'] = __('To Email(s) missing.', 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_VOLUNTEER:

				$ret['success-header'] = __('Awesome! Thanks for taking part!', 'poptheme-wassup');
				$ret['success'] = __("We have sent a message with your information to the organizers. They should contact you soon.", 'poptheme-wassup');
				$ret['empty-whyvolunteer'] = __("\"Why do you want to volunteer?\" is missing.", 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FLAG:

				$ret['success-header'] = __('Flag received successfully.', 'poptheme-wassup');
				$ret['success'] = __("Noted, we will evaluate your feedback and take appropriate action.", 'poptheme-wassup');
				$ret['empty-whyflag'] = __("\"Description on why this post is inappropriate\" is missing.", 'poptheme-wassup');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTER:

				$ret['success-header'] = __('Subscription Successful!', 'poptheme-wassup');
				$ret['success'] = sprintf(
					__("To make sure you get the newsletter, please add <strong>%s</strong> in your contact list. Thanks!", 'poptheme-wassup'),
					PoPTheme_Wassup_EmailSender_Utils::get_newsletter_email()
				);
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTERUNSUBSCRIPTION:

				$ret['success-header'] = __('Unsubscription successful', 'poptheme-wassup');
				$ret['success'] = sprintf(
					'%s<br/>%s',
					__('It is a pity to see you go. Hopefully you will keep visiting our website.', 'poptheme-wassup'),
					sprintf(
						__('And if anything, you can always <a href="%s">contact us</a>.', 'poptheme-wassup'),
						get_permalink(POPTHEME_WASSUP_GF_PAGE_CONTACTUS)
					)
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_GF_Template_Processor_MessageFeedbackLayouts();