<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ReplyCommentViewComponentHeadersBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_VIEWCOMPONENT_HEADER_REPLYCOMMENT;
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		if ($post_template = $this->get_post_template($template_id)) {
			$ret[] = $post_template;
		}
		if ($comment_template = $this->get_comment_template($template_id)) {
			$ret[] = $comment_template;
		}
		
		return $ret;
	}

	function get_post_template($template_id) {

		return null;
	}

	function get_comment_template($template_id) {

		return null;
	}

	function get_inresponseto_title($template_id, $atts) {

		return sprintf(
			'<p><em>%s</em></p>',
			__('In response to:', 'pop-coreprocessors')
		);
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);
	
		global $gd_template_processor_manager;

		$ret[GD_JS_TITLES/*'titles'*/]['inresponseto'] = $this->get_inresponseto_title($template_id, $atts);

		if ($post_template = $this->get_post_template($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['post'] = $gd_template_processor_manager->get_processor($post_template)->get_settings_id($post_template);
		}

		if ($comment_template = $this->get_comment_template($template_id)) {
		
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['comment'] = $gd_template_processor_manager->get_processor($comment_template)->get_settings_id($comment_template);
		}
		
		return $ret;
	}
}
