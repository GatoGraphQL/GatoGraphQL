<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST', PoP_ServerUtils::get_template_definition('block-automatedemails-singlepost'));

class PoPTheme_Wassup_AE_Template_Processor_ContentBlocks extends PoPTheme_Wassup_AutomatedEmails_Template_Processor_ContentBlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST,
		);
	}

	
	// function get_title($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:

	// 			return get_the_title($_REQUEST['pid']);
	// 	}
		
	// 	return parent::get_title($template_id);
	// }

	protected function get_description($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:

				$pid = $_REQUEST['pid'];
				return sprintf(
					'<p>%s</p><h1>%s</h1>',
					sprintf(
						__('Here we send you this special %s:', 'poptheme-wassup-automatedemails'),
						gd_get_postname($pid, 'lc')
					),
					get_the_title($pid)
				);
		}

		return parent::get_description($template_id, $atts);
	}

	// protected function get_description_bottom($template_id, $atts) {
	
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:

	// 			return sprintf(
	// 				'<p>&nbsp;</p>%s',
	// 				PoP_EmailSender_CustomUtils::get_preferences_footer()
	// 			);
	// 	}

	// 	return parent::get_description_bottom($template_id, $atts);
	// }
	
	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:

				// Add the Sidebar on the top
				$pid = $_REQUEST['pid'];
				$post_type = get_post_type($pid);
				$cat = gd_get_the_main_category($pid);
				if ($post_type == EM_POST_TYPE_EVENT) {

					// $ret[] = gd_em_single_event_is_future($pid) ? GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT : GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_PASTEVENT;
					$ret[] = GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_EVENT;
				}
				elseif ($post_type == 'post') {

					// $cats_sidebar = $this->get_sidebars_by_category();
					// if ($cat_sidebar = $cats_sidebar[$cat]) {
					// 	$ret[] = $cat_sidebar;
					// }
					$ret[] = GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_POSTSIDEBARCOMPACT_HORIZONTAL_POST;
				}

				$ret[] = GD_TEMPLATE_CONTENT_SINGLE;
				break;
		}
	
		return $ret;
	}

	// protected function get_sidebars_by_category() {

	// 	return apply_filters(
	// 		'GD_Template_Processor_CustomContentBlocks:get_block_inner_templates:content:sidebar_by_category',
	// 		array()
	// 	);
	// }

	// function init_atts($template_id, &$atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:

	// 			$this->append_att($template_id, $atts, 'class', 'block-single-content');
	// 			break;
	// 	}
		
	// 	return parent::init_atts($template_id, $atts);
	// }

	function get_dataload_source($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:

				return get_permalink($_REQUEST['pid']);
		}
	
		return parent::get_dataload_source($template_id, $atts);
	}

	function get_dataloader($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCK_AUTOMATEDEMAILS_SINGLEPOST:
			
				// Decide on the dataloader based on the post_type of the single element
				$pid = $_REQUEST['pid'];
				$post_type = get_post_type($pid);

				if ($post_type == EM_POST_TYPE_EVENT) {
					return GD_DATALOADER_EDITEVENT;
				}

				return GD_DATALOADER_EDITPOST;
		}
		
		return parent::get_dataloader($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AE_Template_Processor_ContentBlocks();