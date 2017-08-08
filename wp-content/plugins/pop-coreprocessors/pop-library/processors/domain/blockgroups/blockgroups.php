<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN', PoP_ServerUtils::get_template_definition('blockgroup-initializedomain'));

class GD_Core_Template_Processor_BlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN:

				$ret = array_merge(
					$ret,
					GD_Template_Processor_DomainUtils::get_initializedomain_blocks()
				);

				break;
		}

		return $ret;
	}

	function get_title($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN:

				return '';
		}
		
		return parent::get_title($template_id);
	}

	// protected function get_block_page($template_id) {

	// 	global $gd_template_settingsmanager;

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN:

	// 			// Needed so that the blockgroup validates the checkpoint from the page
	// 			if ($page = $gd_template_settingsmanager->get_blockgroup_page($template_id, GD_SETTINGS_HIERARCHY_PAGE)) {

	// 				return $page;
	// 			}
	// 			break;
	// 	}

	// 	return parent::get_block_page($template_id);
	// }

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN:

				return GD_DATALOAD_IOHANDLER_DOMAINBLOCK;
		}
		
		return parent::get_iohandler($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN:

				// Make it invisible, nothing to show
				$this->append_att($template_id, $atts, 'class', 'hidden');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Core_Template_Processor_BlockGroups();
