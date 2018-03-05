<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-contactus'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUSER', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-contactuser'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SHAREBYEMAIL', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-sharebyemail'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_VOLUNTEER', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-volunteer'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FLAG', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-flag'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTER', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-newsletter'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTERUNSUBSCRIPTION', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedback-newsletterunsubscription'));

class GenericForms_Template_Processor_MessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

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

		$ret['empty-name'] = __('Name is missing.', 'pop-genericforms');
		$ret['empty-email'] = __('Email is missing or format is incorrect.', 'pop-genericforms');
		$ret['empty-message'] = __('Message is missing.', 'pop-genericforms');

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUS:

				$ret['success-header'] = __('Message sent successfully!', 'pop-genericforms');
				$ret['success'] = __('Thanks for contacting us, we will get in touch with you shortly.', 'pop-genericforms');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUSER:

				$ret['success-header'] = __('Message sent successfully!', 'pop-genericforms');
				// $ret['success'] = __("Let's keep connecting the green dots.", 'pop-genericforms');
				$ret['success'] = __("Your message has been sent to the user by email.", 'pop-genericforms');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SHAREBYEMAIL:

				$ret['success-header'] = __('Email sent successfully.', 'pop-genericforms');
				$ret['success'] = __("Any more friends who might be interested? Keep sending!", 'pop-genericforms');
				$ret['empty-destination-email'] = __('To Email(s) missing.', 'pop-genericforms');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_VOLUNTEER:

				$ret['success-header'] = __('Awesome! Thanks for taking part!', 'pop-genericforms');
				$ret['success'] = __("We have sent a message with your information to the organizers. They should contact you soon.", 'pop-genericforms');
				$ret['empty-whyvolunteer'] = __("\"Why do you want to volunteer?\" is missing.", 'pop-genericforms');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FLAG:

				$ret['success-header'] = __('Flag received successfully.', 'pop-genericforms');
				$ret['success'] = __("Noted, we will evaluate your feedback and take appropriate action.", 'pop-genericforms');
				$ret['empty-whyflag'] = __("\"Description on why this post is inappropriate\" is missing.", 'pop-genericforms');
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTER:

				$ret['success-header'] = __('Subscription Successful!', 'pop-genericforms');
				$ret['success'] = sprintf(
					__("To make sure you get the newsletter, please add <strong>%s</strong> in your contact list. Thanks!", 'pop-genericforms'),
					PoP_GenericForms_EmailSender_Utils::get_newsletter_email()
				);
				break;

			case GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTERUNSUBSCRIPTION:

				$ret['success-header'] = __('Unsubscription successful', 'pop-genericforms');
				$ret['success'] = sprintf(
					'%s<br/>%s',
					__('It is a pity to see you go. Hopefully you will keep visiting our website.', 'pop-genericforms'),
					sprintf(
						__('And if anything, you can always <a href="%s">contact us</a>.', 'pop-genericforms'),
						get_permalink(POP_GENERICFORMS_PAGE_CONTACTUS)
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
new GenericForms_Template_Processor_MessageFeedbackLayouts();