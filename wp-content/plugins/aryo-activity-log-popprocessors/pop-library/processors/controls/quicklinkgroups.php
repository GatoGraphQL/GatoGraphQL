<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_AAL_TEMPLATE_QUICKLINKGROUP_NOTIFICATION', PoP_TemplateIDUtils::get_template_definition('aal-quicklinkgroup-notification'));

class GD_AAL_Template_Processor_QuicklinkGroups extends GD_Template_Processor_ControlGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_AAL_TEMPLATE_QUICKLINKGROUP_NOTIFICATION,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {
				
			case GD_AAL_TEMPLATE_QUICKLINKGROUP_NOTIFICATION:

				$ret[] = GD_AAL_TEMPLATE_QUICKLINKBUTTONGROUP_NOTIFICATION_MARKASREADUNREAD;
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_AAL_Template_Processor_QuicklinkGroups();