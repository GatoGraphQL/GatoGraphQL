<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EM_TEMPLATE_QUICKLINKBUTTONGROUP_DOWNLOADLINKS', PoP_ServerUtils::get_template_definition('em-quicklinkbuttongroup-downloadlinks'));

class GD_EM_Template_Processor_QuicklinkButtonGroups extends GD_Template_Processor_ControlButtonGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_EM_TEMPLATE_QUICKLINKBUTTONGROUP_DOWNLOADLINKS,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		switch ($template_id) {
		
			case GD_EM_TEMPLATE_QUICKLINKBUTTONGROUP_DOWNLOADLINKS:

				$ret[] = GD_EM_TEMPLATE_DROPDOWNBUTTONQUICKLINK_DOWNLOADLINKS;
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_QuicklinkButtonGroups();