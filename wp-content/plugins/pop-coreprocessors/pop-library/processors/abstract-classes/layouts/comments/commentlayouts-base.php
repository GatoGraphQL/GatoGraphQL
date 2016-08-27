<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CommentLayoutsBase extends GD_Template_ProcessorBase {

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);
		$ret[] = $this->get_btnreply_template($template_id);

		if ($abovelayout_templates = $this->get_abovelayout_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$abovelayout_templates
			);
		}
		if ($content_template = $this->get_content_template($template_id)) {
			$ret[] = $content_template;
		}
		return $ret;
	}

	function get_content_template($template_id) {

		return GD_TEMPLATE_LAYOUT_CONTENT_COMMENT;
	}

	function get_abovelayout_layouts($template_id) {

		return array();
		// return array(
		// 	// Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
		// 	GD_TEMPLATE_LAYOUT_COMMENTUSERMENTIONS,
		// );
	}

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_LAYOUT_COMMENT;
	}

	function get_btnreply_template($template_id) {
	
		return GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY;
	}

	function get_authorname_template($template_id) {
	
		return GD_TEMPLATE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR;
	}

	function get_authoravatar_template($template_id) {
	
		return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR;
	}

	function get_subcomponent_modules($template_id) {

		$ret = parent::get_subcomponent_modules($template_id);
	
		$ret['author'] = array(
			'modules' => array(
				$this->get_authorname_template($template_id), 
				$this->get_authoravatar_template($template_id),
			),
			'dataloader' => GD_DATALOADER_USERLIST
		);
		
		return $ret;
	}

	function is_runtime_added($template_id, $atts) {

		return false;
	}
	
	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);
	
		$ret = array_merge(
			$ret,
			array(/*'content', *//*'parent',*/ 'date')
		);

		// if ($this->is_runtime_added($template_id, $atts)) {

		// 	$ret[] = 'post-id';
		// }
		
		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$btnreply = $this->get_btnreply_template($template_id);
		$authorname = $this->get_authorname_template($template_id);
		$authoravatar = $this->get_authoravatar_template($template_id);
		
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['btn-replycomment'] = $gd_template_processor_manager->get_processor($btnreply)->get_settings_id($btnreply);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['authorname'] = $gd_template_processor_manager->get_processor($authorname)->get_settings_id($authorname);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['authoravatar'] = $gd_template_processor_manager->get_processor($authoravatar)->get_settings_id($authoravatar);

		if ($content_template = $this->get_content_template($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['content'] = $gd_template_processor_manager->get_processor($content_template)->get_settings_id($content_template);
		}

		if ($abovelayout_templates = $this->get_abovelayout_layouts($template_id)) {
	
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['abovelayout'] = $abovelayout_templates;
			foreach ($abovelayout_templates as $abovelayout_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$abovelayout_template] = $gd_template_processor_manager->get_processor($abovelayout_template)->get_settings_id($abovelayout_template);
			}
		}

		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		// // Make the images inside img-responsive
		// $this->add_jsmethod($ret, 'imageResponsive');

		// // Add the popover for the @mentions
		// $this->add_jsmethod($ret, 'contentPopover');

		if ($this->is_runtime_added($template_id, $atts)) {

			// The 2 functions below keep them in this order: first must open the collapse, only then can scroll down to that position

			// Also add the collapse if the comment is inside the collapse. Eg: SimpleView Feed
			$this->add_jsmethod($ret, 'openParentCollapse');

			// Highlight the comment when the user just adds it
			$this->add_jsmethod($ret, 'highlight');
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		if ($this->is_runtime_added($template_id, $atts)) {

			$this->append_att($template_id, $atts, 'class', 'pop-highlight');
		}

		// // Hide the @mentions popover code
		// if (in_array(GD_TEMPLATE_LAYOUT_COMMENTUSERMENTIONS, $this->get_modules($template_id))) {
		// 	$this->append_att(GD_TEMPLATE_LAYOUT_COMMENTUSERMENTIONS, $atts, 'class', 'hidden');
		// }
		
		return parent::init_atts($template_id, $atts);
	}
}