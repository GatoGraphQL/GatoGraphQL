<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_LOGGEDINUSERDATA', PoP_ServerUtils::get_template_definition('blockgroup-loggedinuserdata'));

class GD_Template_Processor_UserAccountBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_LOGGEDINUSERDATA,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_LOGGEDINUSERDATA:

				// Allow Aryo to add the "Latest Notifications" block
				// $blocks = apply_filters(
				// 	'GD_Template_Processor_UserAccountBlockGroups:blockgroup_blocks',
				// 	array(
				// 		GD_TEMPLATE_BLOCKDATA_FOLLOWSUSERS,
				// 		GD_TEMPLATE_BLOCKDATA_RECOMMENDSPOSTS,
				// 		GD_TEMPLATE_BLOCKDATA_UPVOTESPOSTS,
				// 		GD_TEMPLATE_BLOCKDATA_DOWNVOTESPOSTS,
				// 	)
				// );
				$ret = array_merge(
					$ret,
					GD_Template_Processor_UserAccountUtils::get_loggedinuserdata_blocks()
				);
				break;
		}

		return $ret;
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCKGROUP_LOGGEDINUSERDATA:

				$this->append_att($template_id, $atts, 'class', 'hidden');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserAccountBlockGroups();
