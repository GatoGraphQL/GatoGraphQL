<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreateUserFormMesageFeedbackLayoutsBase extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);
			
		$ret['success-header'] = __('Awesome! Your User Account was created successfully.', 'pop-coreprocessors');
		
		// Allow PoPTheme Wassup to add the emails to whitelist
		$ret['success'] = apply_filters(
			'GD_Template_Processor_CreateUserFormMesageFeedbackLayoutsBase:success:msg',
			sprintf(
				'<p>%s</p>',
				sprintf(
					__('Please <a href="%s">click here to log-in</a>.', 'pop-coreprocessors'),
					wp_login_url()
				)
			)
		);

		return $ret;
	}
}