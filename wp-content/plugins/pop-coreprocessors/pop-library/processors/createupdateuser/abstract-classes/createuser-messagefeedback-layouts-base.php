<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreateUserFormMesageFeedbackLayoutsBase extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);
			
		$ret['success-header'] = __('Awesome! Your User Account was created successfully!', 'pop-coreprocessors');
		$ret['success'] = sprintf(
			'<p>%s</p>%s<p>%s</p>',
			__('Please add the following emails to your contact list, to make sure you receive our notifications:', 'pop-coreprocessors'),
			sprintf(
				'<ul><li>%s</li><li>%s</li></ul>',
				gd_email_info_email(),
				gd_email_newsletter_email()
			),
			sprintf(
				__('Please <a href="%s">click here to log-in</a>.', 'pop-coreprocessors'),
				wp_login_url()
			)
		);

		return $ret;
	}
}