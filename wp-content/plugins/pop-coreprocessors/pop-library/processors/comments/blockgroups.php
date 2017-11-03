<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_ADDCOMMENT', PoP_TemplateIDUtils::get_template_definition('blockgroup-addcomment'));
define ('GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT', PoP_TemplateIDUtils::get_template_definition('blockgroup-replicateaddcomment'));

class GD_Template_Processor_CommentsBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_ADDCOMMENT,
			GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT,
		);
	}

	function get_dataload_source($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_ADDCOMMENT:
			case GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT:
			
				// The BlockGroup needs the dataload-source so that it can be intercepted and add the Add Comment Addon
				return get_permalink(POP_WPAPI_PAGE_ADDCOMMENT);
		}

		return parent::get_dataload_source($template_id, $atts);
	}

	function get_activeblock_selector($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_ADDCOMMENT:
			case GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT:
			
				return null;
		}

		return parent::get_activeblock_selector($template_id, $atts);
	}

	protected function get_initjs_blockchildren($template_id, $atts) {

		$ret = parent::get_initjs_blockchildren($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_ADDCOMMENT:
			case GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT:
			
				$ret[] = array(
					'div.blocksection-extensions',
					// 'div.blockwrapper',
					'div.pop-block'
				);
				break;
		}
		
		return $ret;
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_ADDCOMMENT:

				$ret[] = GD_TEMPLATE_BLOCK_POSTHEADER;
				$ret[] = GD_TEMPLATE_BLOCK_COMMENTSINGLE;
				$ret[] = GD_TEMPLATE_BLOCK_ADDCOMMENT;
				$ret[] = GD_TEMPLATE_BLOCKDATA_ADDCOMMENT;
				break;

			case GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT:

				$ret[] = GD_TEMPLATE_BLOCK_ADDCOMMENT;
				$ret[] = GD_TEMPLATE_BLOCKDATA_ADDCOMMENT;
				break;
		}

		return $ret;
	}

	function get_title($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCKGROUP_ADDCOMMENT:

				return gd_navigation_menu_item(POP_WPAPI_PAGE_ADDCOMMENT, true).__('Add Comment', 'pop-coreprocessors');
		}
		
		return parent::get_title($template_id);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {
		
		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_ADDCOMMENT) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');
				}
				break;

			case GD_TEMPLATE_BLOCKGROUP_ADDCOMMENT:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_ADDCOMMENT) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_POSTHEADER) {

					$description = __('Add a comment for:', 'pop-coreprocessors');
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_COMMENTSINGLE) {

					$description = sprintf(
						'<em>%s</em>',
						__('In response to:', 'pop-coreprocessors')
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'hidden-if-empty', true);

					// Hide the 'Reply' button
					$this->append_att(GD_TEMPLATE_VIEWCOMPONENT_BUTTON_COMMENT_REPLY, $blockgroup_block_atts, 'class', 'hidden');
				}
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentsBlockGroups();
