<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_FOLLOWUSER', PoP_TemplateIDUtils::get_template_definition('blockgroup-followuser'));
define ('GD_TEMPLATE_BLOCKGROUP_UNFOLLOWUSER', PoP_TemplateIDUtils::get_template_definition('blockgroup-unfollowuser'));
define ('GD_TEMPLATE_BLOCKGROUP_RECOMMENDPOST', PoP_TemplateIDUtils::get_template_definition('blockgroup-recommendpost'));
define ('GD_TEMPLATE_BLOCKGROUP_UNRECOMMENDPOST', PoP_TemplateIDUtils::get_template_definition('blockgroup-unrecommendpost'));
define ('GD_TEMPLATE_BLOCKGROUP_SUBSCRIBETOTAG', PoP_TemplateIDUtils::get_template_definition('blockgroup-subscribetotag'));
define ('GD_TEMPLATE_BLOCKGROUP_UNSUBSCRIBEFROMTAG', PoP_TemplateIDUtils::get_template_definition('blockgroup-unsubscribefromtag'));
define ('GD_TEMPLATE_BLOCKGROUP_UPVOTEPOST', PoP_TemplateIDUtils::get_template_definition('blockgroup-upvotepost'));
define ('GD_TEMPLATE_BLOCKGROUP_UNDOUPVOTEPOST', PoP_TemplateIDUtils::get_template_definition('blockgroup-undoupvotepost'));
define ('GD_TEMPLATE_BLOCKGROUP_DOWNVOTEPOST', PoP_TemplateIDUtils::get_template_definition('blockgroup-downvotepost'));
define ('GD_TEMPLATE_BLOCKGROUP_UNDODOWNVOTEPOST', PoP_TemplateIDUtils::get_template_definition('blockgroup-undodownvotepost'));

class GD_Template_Processor_FunctionsBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_FOLLOWUSER,
			GD_TEMPLATE_BLOCKGROUP_UNFOLLOWUSER,
			GD_TEMPLATE_BLOCKGROUP_RECOMMENDPOST,
			GD_TEMPLATE_BLOCKGROUP_UNRECOMMENDPOST,
			GD_TEMPLATE_BLOCKGROUP_SUBSCRIBETOTAG,
			GD_TEMPLATE_BLOCKGROUP_UNSUBSCRIBEFROMTAG,
			GD_TEMPLATE_BLOCKGROUP_UPVOTEPOST,
			GD_TEMPLATE_BLOCKGROUP_UNDOUPVOTEPOST,
			GD_TEMPLATE_BLOCKGROUP_DOWNVOTEPOST,
			GD_TEMPLATE_BLOCKGROUP_UNDODOWNVOTEPOST,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_FOLLOWUSER:

				$ret[] = GD_TEMPLATE_ACTION_FOLLOWUSER;
				$ret[] = GD_TEMPLATE_BLOCKDATA_FOLLOWUSER;
				break;

			case GD_TEMPLATE_BLOCKGROUP_UNFOLLOWUSER:

				$ret[] = GD_TEMPLATE_ACTION_UNFOLLOWUSER;
				$ret[] = GD_TEMPLATE_BLOCKDATA_UNFOLLOWUSER;
				break;

			case GD_TEMPLATE_BLOCKGROUP_RECOMMENDPOST:

				$ret[] = GD_TEMPLATE_ACTION_RECOMMENDPOST;
				$ret[] = GD_TEMPLATE_BLOCKDATA_RECOMMENDPOST;
				break;

			case GD_TEMPLATE_BLOCKGROUP_UNRECOMMENDPOST:

				$ret[] = GD_TEMPLATE_ACTION_UNRECOMMENDPOST;
				$ret[] = GD_TEMPLATE_BLOCKDATA_UNRECOMMENDPOST;
				break;

			case GD_TEMPLATE_BLOCKGROUP_SUBSCRIBETOTAG:

				$ret[] = GD_TEMPLATE_ACTION_SUBSCRIBETOTAG;
				$ret[] = GD_TEMPLATE_BLOCKDATA_SUBSCRIBETOTAG;
				break;

			case GD_TEMPLATE_BLOCKGROUP_UNSUBSCRIBEFROMTAG:

				$ret[] = GD_TEMPLATE_ACTION_UNSUBSCRIBEFROMTAG;
				$ret[] = GD_TEMPLATE_BLOCKDATA_UNSUBSCRIBEFROMTAG;
				break;

			case GD_TEMPLATE_BLOCKGROUP_UPVOTEPOST:

				$ret[] = GD_TEMPLATE_ACTION_UPVOTEPOST;
				$ret[] = GD_TEMPLATE_BLOCKDATA_UPVOTEPOST;
				break;

			case GD_TEMPLATE_BLOCKGROUP_UNDOUPVOTEPOST:

				$ret[] = GD_TEMPLATE_ACTION_UNDOUPVOTEPOST;
				$ret[] = GD_TEMPLATE_BLOCKDATA_UNDOUPVOTEPOST;
				break;

			case GD_TEMPLATE_BLOCKGROUP_DOWNVOTEPOST:

				$ret[] = GD_TEMPLATE_ACTION_DOWNVOTEPOST;
				$ret[] = GD_TEMPLATE_BLOCKDATA_DOWNVOTEPOST;
				break;

			case GD_TEMPLATE_BLOCKGROUP_UNDODOWNVOTEPOST:

				$ret[] = GD_TEMPLATE_ACTION_UNDODOWNVOTEPOST;
				$ret[] = GD_TEMPLATE_BLOCKDATA_UNDODOWNVOTEPOST;
				break;
		}

		return $ret;
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FunctionsBlockGroups();
