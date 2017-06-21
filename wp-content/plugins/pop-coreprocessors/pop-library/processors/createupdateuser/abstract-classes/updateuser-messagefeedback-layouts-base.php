<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UpdateUserFormMesageFeedbackLayoutsBase extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);
			
		$vars = GD_TemplateManager_Utils::get_vars();
		$ret['success-header'] = __('User Account updated successfully.', 'pop-coreprocessors');
		$ret['success'] = sprintf(
			__('View your <a href="%s">updated user account</a>.', 'pop-coreprocessors'),
			get_author_posts_url($vars['global-state']['current-user-id']/*get_current_user_id()*/)
		);

		return $ret;
	}
}