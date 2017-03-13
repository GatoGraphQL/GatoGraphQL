<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GETPOPDEMO_TEMPLATE_BLOCKGROUP_HOME', PoP_ServerUtils::get_template_definition('blockgroup-getpopdemo-home'));

class GetPoPDemo_Template_Processor_CustomBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GETPOPDEMO_TEMPLATE_BLOCKGROUP_HOME,
		);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
	
		switch ($template_id) {

			case GETPOPDEMO_TEMPLATE_BLOCKGROUP_HOME:

				// Needed to recalculate the waypoints for the sideinfo waypoints effect that shows the filter when reaching the top of the All Content block
				$this->add_jsmethod($ret, 'onBootstrapEventWindowResize');

				// It will remove class "hidden" through .js if there is no cookie
				$this->add_jsmethod($ret, 'cookieToggleClass');
				break;
		}

		return $ret;
	}

	function get_blockgroup_blockgroups($template_id) {

		$ret = parent::get_blockgroup_blockgroups($template_id);

		switch ($template_id) {

			case GETPOPDEMO_TEMPLATE_BLOCKGROUP_HOME:

				$ret[] = GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME;
				$ret[] = GETPOPDEMO_TEMPLATE_BLOCKGROUP_COLLAPSE_HOMETOP;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoPDemo_Template_Processor_CustomBlockGroups();
