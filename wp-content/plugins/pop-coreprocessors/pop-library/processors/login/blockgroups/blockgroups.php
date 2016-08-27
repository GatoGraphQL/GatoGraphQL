<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_LOGIN', PoP_ServerUtils::get_template_definition('blockgroup-login'));

class GD_Template_Processor_LoginBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_LOGIN,
		);
	}

	function get_dataload_source($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_LOGIN:
			
				// The BlockGroup needs the dataload-source so that it can be intercepted and add the Add Comment Addon
				return get_permalink(POP_WPAPI_PAGE_LOGIN);
		}

		return parent::get_dataload_source($template_id, $atts);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_LOGIN:
			
				// Needed to show the title in the tab. It will be hidden by css in the page.
				return get_the_title(POP_WPAPI_PAGE_LOGIN);
		}

		return parent::get_title($template_id);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_LOGIN:

				// // Allow WSL to add the FB/Twitter Login
				// $blocks = apply_filters(
				// 	'GD_Template_Processor_LoginBlockGroups:blockgroup_blocks',
				// 	array(
				// 		GD_TEMPLATE_BLOCK_LOGIN,
				// 		GD_TEMPLATE_BLOCK_FOLLOWSUSERS,
				// 		GD_TEMPLATE_BLOCK_RECOMMENDSPOSTS,
				// 		GD_TEMPLATE_BLOCK_UPVOTESPOSTS,
				// 		GD_TEMPLATE_BLOCK_DOWNVOTESPOSTS,
				// 	)
				// );
				$ret[] = GD_TEMPLATE_BLOCK_LOGIN;
				$ret = array_merge(
					$ret,
					GD_Template_Processor_UserAccountUtils::get_login_blocks()
				);

				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_LOGIN:
			
				$this->append_att($template_id, $atts, 'class', 'blockgroup-login');
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {
		
		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_LOGIN:

				// Make all contained blocks 'mainblock'. Otherwise, the Login Block will not have the submenu
				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'is-mainblock', true);

				// Allow to set $atts for the extra blocks. Eg: WSL setting the loginBlock for setting the disabled layer
				$hooks = apply_filters(
					'GD_Template_Processor_LoginBlockGroups:blockgroup_block_atts:hooks',
					array()
				);
				foreach ($hooks as $hook) {
					$hook->init_atts_blockgroup_block($this, $blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
				}
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LoginBlockGroups();
