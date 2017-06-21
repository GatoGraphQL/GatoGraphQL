<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_FOLLOWSUSERS', PoP_ServerUtils::get_template_definition('block-followsusers'));
define ('GD_TEMPLATE_BLOCK_RECOMMENDSPOSTS', PoP_ServerUtils::get_template_definition('block-recommendsposts'));
define ('GD_TEMPLATE_BLOCK_SUBSCRIBESTOTAGS', PoP_ServerUtils::get_template_definition('block-subscribestotags'));
define ('GD_TEMPLATE_BLOCK_UPVOTESPOSTS', PoP_ServerUtils::get_template_definition('block-upvotesposts'));
define ('GD_TEMPLATE_BLOCK_DOWNVOTESPOSTS', PoP_ServerUtils::get_template_definition('block-downvotesposts'));
define ('GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS', PoP_ServerUtils::get_template_definition('blockdata-followsusers'));
define ('GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS', PoP_ServerUtils::get_template_definition('blockdata-recommendsposts'));
define ('GD_TEMPLATE_BLOCKDATA_SUBSCRIBESTOTAGS', PoP_ServerUtils::get_template_definition('blockdata-subscribestotags'));
define ('GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS', PoP_ServerUtils::get_template_definition('blockdata-upvotessposts'));
define ('GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS', PoP_ServerUtils::get_template_definition('blockdata-downvotesposts'));
define ('GD_TEMPLATE_BLOCKDATA_FOLLOWUSER', PoP_ServerUtils::get_template_definition('blockdata-followuser'));
define ('GD_TEMPLATE_BLOCKDATA_UNFOLLOWUSER', PoP_ServerUtils::get_template_definition('blockdata-unfollowuser'));
define ('GD_TEMPLATE_BLOCKDATA_RECOMMENDPOST', PoP_ServerUtils::get_template_definition('blockdata-recommendpost'));
define ('GD_TEMPLATE_BLOCKDATA_UNRECOMMENDPOST', PoP_ServerUtils::get_template_definition('blockdata-unrecommendpost'));
define ('GD_TEMPLATE_BLOCKDATA_SUBSCRIBETOTAG', PoP_ServerUtils::get_template_definition('blockdata-subscribetotag'));
define ('GD_TEMPLATE_BLOCKDATA_UNSUBSCRIBEFROMTAG', PoP_ServerUtils::get_template_definition('blockdata-unsubscribefromtag'));
define ('GD_TEMPLATE_BLOCKDATA_UPVOTEPOST', PoP_ServerUtils::get_template_definition('blockdata-upvotepost'));
define ('GD_TEMPLATE_BLOCKDATA_UNDOUPVOTEPOST', PoP_ServerUtils::get_template_definition('blockdata-undoupvotepost'));
define ('GD_TEMPLATE_BLOCKDATA_DOWNVOTEPOST', PoP_ServerUtils::get_template_definition('blockdata-downvotepost'));
define ('GD_TEMPLATE_BLOCKDATA_UNDODOWNVOTEPOST', PoP_ServerUtils::get_template_definition('blockdata-undodownvotepost'));

class GD_Template_Processor_FunctionsBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_FOLLOWSUSERS,
			GD_TEMPLATE_BLOCK_RECOMMENDSPOSTS,
			GD_TEMPLATE_BLOCK_SUBSCRIBESTOTAGS,
			GD_TEMPLATE_BLOCK_UPVOTESPOSTS,
			GD_TEMPLATE_BLOCK_DOWNVOTESPOSTS,
			GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS,
			GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS,
			GD_TEMPLATE_BLOCKDATA_SUBSCRIBESTOTAGS,
			GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS,
			GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS,
			GD_TEMPLATE_BLOCKDATA_FOLLOWUSER,
			GD_TEMPLATE_BLOCKDATA_UNFOLLOWUSER,
			GD_TEMPLATE_BLOCKDATA_RECOMMENDPOST,
			GD_TEMPLATE_BLOCKDATA_UNRECOMMENDPOST,
			GD_TEMPLATE_BLOCKDATA_SUBSCRIBETOTAG,
			GD_TEMPLATE_BLOCKDATA_UNSUBSCRIBEFROMTAG,
			GD_TEMPLATE_BLOCKDATA_UPVOTEPOST,
			GD_TEMPLATE_BLOCKDATA_UNDOUPVOTEPOST,
			GD_TEMPLATE_BLOCKDATA_DOWNVOTEPOST,
			GD_TEMPLATE_BLOCKDATA_UNDODOWNVOTEPOST,
		);
	}

	function get_settings_id($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS:

				return GD_TEMPLATE_BLOCK_FOLLOWSUSERS;
				
			case GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS:
		
				return GD_TEMPLATE_BLOCK_RECOMMENDSPOSTS;
				
			case GD_TEMPLATE_BLOCKDATA_SUBSCRIBESTOTAGS:
		
				return GD_TEMPLATE_BLOCK_SUBSCRIBESTOTAGS;
				
			case GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS:
		
				return GD_TEMPLATE_BLOCK_UPVOTESPOSTS;
				
			case GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS:
		
				return GD_TEMPLATE_BLOCK_DOWNVOTESPOSTS;
		}

		return parent::get_settings_id($template_id);
	}

	function integrate_execution_bag($template_id, $atts, &$data_settings, $execution_bag) {	

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_FOLLOWUSER:
			case GD_TEMPLATE_BLOCKDATA_UNFOLLOWUSER:
			case GD_TEMPLATE_BLOCKDATA_RECOMMENDPOST:
			case GD_TEMPLATE_BLOCKDATA_UNRECOMMENDPOST:
			case GD_TEMPLATE_BLOCKDATA_SUBSCRIBETOTAG:
			case GD_TEMPLATE_BLOCKDATA_UNSUBSCRIBEFROMTAG:
			case GD_TEMPLATE_BLOCKDATA_UPVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_UNDOUPVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_DOWNVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_UNDODOWNVOTEPOST:

				$actions = array(
					GD_TEMPLATE_BLOCKDATA_FOLLOWUSER => GD_TEMPLATE_ACTION_FOLLOWUSER,
					GD_TEMPLATE_BLOCKDATA_UNFOLLOWUSER => GD_TEMPLATE_ACTION_UNFOLLOWUSER,
					GD_TEMPLATE_BLOCKDATA_RECOMMENDPOST => GD_TEMPLATE_ACTION_RECOMMENDPOST,
					GD_TEMPLATE_BLOCKDATA_UNRECOMMENDPOST => GD_TEMPLATE_ACTION_UNRECOMMENDPOST,
					GD_TEMPLATE_BLOCKDATA_SUBSCRIBETOTAG => GD_TEMPLATE_ACTION_SUBSCRIBETOTAG,
					GD_TEMPLATE_BLOCKDATA_UNSUBSCRIBEFROMTAG => GD_TEMPLATE_ACTION_UNSUBSCRIBEFROMTAG,
					GD_TEMPLATE_BLOCKDATA_UPVOTEPOST => GD_TEMPLATE_ACTION_UPVOTEPOST,
					GD_TEMPLATE_BLOCKDATA_UNDOUPVOTEPOST => GD_TEMPLATE_ACTION_UNDOUPVOTEPOST,
					GD_TEMPLATE_BLOCKDATA_DOWNVOTEPOST => GD_TEMPLATE_ACTION_DOWNVOTEPOST,
					GD_TEMPLATE_BLOCKDATA_UNDODOWNVOTEPOST => GD_TEMPLATE_ACTION_UNDODOWNVOTEPOST,
				);

				foreach ($execution_bag as $pagesection => $pagesection_execution_bag) {
					foreach ($pagesection_execution_bag as $block => $block_execution_bag) {

						// If the block is the previous action, having then taken place
						if ($block == $actions[$template_id]) {

							// It is the AddComment Execution Bag. Get the dataset
							if ($dataset = $block_execution_bag['dataset']) {
								$data_settings['dataload-atts']['include'] = $dataset;
								$data_settings[GD_DATALOAD_LOAD] = true;
							}
							break;
						}						
					}
				}
				break;

			case GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS:
			case GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS:
			case GD_TEMPLATE_BLOCKDATA_SUBSCRIBESTOTAGS:
			case GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS:
			case GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS:

				// If the user is logged in, the GD_TEMPLATE_ACTION_LOGIN was successful, then load the data
				$vars = GD_TemplateManager_Utils::get_vars();
				if ($vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {

					$data_settings[GD_DATALOAD_LOAD] = true;
				}
				break;
		}

		return parent::integrate_execution_bag($template_id, $atts, $data_settings, $execution_bag);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$layouts = array(
			GD_TEMPLATE_BLOCK_FOLLOWSUSERS => GD_TEMPLATE_CONTENT_FOLLOWSUSERS,
			GD_TEMPLATE_BLOCK_RECOMMENDSPOSTS => GD_TEMPLATE_CONTENT_RECOMMENDSPOSTS,
			GD_TEMPLATE_BLOCK_SUBSCRIBESTOTAGS => GD_TEMPLATE_CONTENT_SUBSCRIBESTOTAGS,
			GD_TEMPLATE_BLOCK_UPVOTESPOSTS => GD_TEMPLATE_CONTENT_UPVOTESPOSTS,
			GD_TEMPLATE_BLOCK_DOWNVOTESPOSTS => GD_TEMPLATE_CONTENT_DOWNVOTESPOSTS,
			GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS => GD_TEMPLATE_CONTENT_FOLLOWSUSERS,
			GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS => GD_TEMPLATE_CONTENT_RECOMMENDSPOSTS,
			GD_TEMPLATE_BLOCKDATA_SUBSCRIBESTOTAGS => GD_TEMPLATE_CONTENT_SUBSCRIBESTOTAGS,
			GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS => GD_TEMPLATE_CONTENT_UPVOTESPOSTS,
			GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS => GD_TEMPLATE_CONTENT_DOWNVOTESPOSTS,
			GD_TEMPLATE_BLOCKDATA_FOLLOWUSER => GD_TEMPLATE_CONTENT_FOLLOWSUSERS,
			GD_TEMPLATE_BLOCKDATA_UNFOLLOWUSER => GD_TEMPLATE_CONTENT_UNFOLLOWSUSERS,
			GD_TEMPLATE_BLOCKDATA_RECOMMENDPOST => GD_TEMPLATE_CONTENT_RECOMMENDSPOSTS,
			GD_TEMPLATE_BLOCKDATA_UNRECOMMENDPOST => GD_TEMPLATE_CONTENT_UNRECOMMENDSPOSTS,
			GD_TEMPLATE_BLOCKDATA_SUBSCRIBETOTAG => GD_TEMPLATE_CONTENT_SUBSCRIBESTOTAGS,
			GD_TEMPLATE_BLOCKDATA_UNSUBSCRIBEFROMTAG => GD_TEMPLATE_CONTENT_UNSUBSCRIBESFROMTAGS,
			GD_TEMPLATE_BLOCKDATA_UPVOTEPOST => GD_TEMPLATE_CONTENT_UPVOTESPOSTS,
			GD_TEMPLATE_BLOCKDATA_UNDOUPVOTEPOST => GD_TEMPLATE_CONTENT_UNDOUPVOTESPOSTS,
			GD_TEMPLATE_BLOCKDATA_DOWNVOTEPOST => GD_TEMPLATE_CONTENT_DOWNVOTESPOSTS,
			GD_TEMPLATE_BLOCKDATA_UNDODOWNVOTEPOST => GD_TEMPLATE_CONTENT_UNDODOWNVOTESPOSTS,
		);
		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}
	
		return $ret;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_FOLLOWSUSERS:

				return GD_DATALOADER_NOUSERS;

			case GD_TEMPLATE_BLOCK_RECOMMENDSPOSTS:
			case GD_TEMPLATE_BLOCK_UPVOTESPOSTS:
			case GD_TEMPLATE_BLOCK_DOWNVOTESPOSTS:

				return GD_DATALOADER_NOPOSTS;

			case GD_TEMPLATE_BLOCK_SUBSCRIBESTOTAGS:

				return GD_DATALOADER_NOTAGS;

			case GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS:

				return GD_DATALOADER_USERFOLLOWSUSERS;

			case GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS:

				return GD_DATALOADER_USERRECOMMENDSPOSTS;

			case GD_TEMPLATE_BLOCKDATA_SUBSCRIBESTOTAGS:

				return GD_DATALOADER_USERSUBSCRIBESTOTAGS;

			case GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS:

				return GD_DATALOADER_USERUPVOTESPOSTS;

			case GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS:

				return GD_DATALOADER_USERDOWNVOTESPOSTS;

			case GD_TEMPLATE_BLOCKDATA_FOLLOWUSER:
			case GD_TEMPLATE_BLOCKDATA_UNFOLLOWUSER:

				return GD_DATALOADER_USERLIST;

			case GD_TEMPLATE_BLOCKDATA_RECOMMENDPOST:
			case GD_TEMPLATE_BLOCKDATA_UNRECOMMENDPOST:
			case GD_TEMPLATE_BLOCKDATA_UPVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_UNDOUPVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_DOWNVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_UNDODOWNVOTEPOST:

				return GD_DATALOADER_POSTLIST;

			case GD_TEMPLATE_BLOCKDATA_SUBSCRIBETOTAG:
			case GD_TEMPLATE_BLOCKDATA_UNSUBSCRIBEFROMTAG:

				return GD_DATALOADER_TAGLIST;
		}
		
		return parent::get_dataloader($template_id);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS:
			case GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS:
			case GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS:
			case GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS:
			case GD_TEMPLATE_BLOCKDATA_FOLLOWUSER:
			case GD_TEMPLATE_BLOCKDATA_UNFOLLOWUSER:
			case GD_TEMPLATE_BLOCKDATA_RECOMMENDPOST:
			case GD_TEMPLATE_BLOCKDATA_UNRECOMMENDPOST:
			case GD_TEMPLATE_BLOCKDATA_SUBSCRIBETOTAG:
			case GD_TEMPLATE_BLOCKDATA_UNSUBSCRIBEFROMTAG:
			case GD_TEMPLATE_BLOCKDATA_UPVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_UNDOUPVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_DOWNVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_UNDODOWNVOTEPOST:

				$this->add_att($template_id, $atts, 'data-load', false);	
				$this->append_att($template_id, $atts, 'class', 'hidden');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCK_FOLLOWSUSERS:
			case GD_TEMPLATE_BLOCK_RECOMMENDSPOSTS:
			case GD_TEMPLATE_BLOCK_UPVOTESPOSTS:
			case GD_TEMPLATE_BLOCK_DOWNVOTESPOSTS:
			case GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS:
			case GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS:
			case GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS:
			case GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS:
			case GD_TEMPLATE_BLOCKDATA_FOLLOWUSER:
			case GD_TEMPLATE_BLOCKDATA_UNFOLLOWUSER:
			case GD_TEMPLATE_BLOCKDATA_RECOMMENDPOST:
			case GD_TEMPLATE_BLOCKDATA_UNRECOMMENDPOST:
			case GD_TEMPLATE_BLOCKDATA_SUBSCRIBETOTAG:
			case GD_TEMPLATE_BLOCKDATA_UNSUBSCRIBEFROMTAG:
			case GD_TEMPLATE_BLOCKDATA_UPVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_UNDOUPVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_DOWNVOTEPOST:
			case GD_TEMPLATE_BLOCKDATA_UNDODOWNVOTEPOST:

				return GD_DATALOAD_IOHANDLER_LIST;
		}
		
		return parent::get_iohandler($template_id);
	}

	function get_data_setting($template_id, $atts) {

		$ret = parent::get_data_setting($template_id, $atts);
	
		switch ($template_id) {

			// case GD_TEMPLATE_BLOCK_FOLLOWSUSERS:
			// case GD_TEMPLATE_BLOCK_RECOMMENDSPOSTS:
			case GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS:
			case GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS:
			case GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS:
			case GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS:
						
				// Bring all of them and then don't bring anymore
				$ret['iohandler-atts'][GD_DATALOAD_IOHANDLER_LIST_STOPFETCHING] = true;
				$ret['dataload-atts']['limit'] = -1;
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FunctionsBlocks();