<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ANCHORMENU', PoP_ServerUtils::get_template_definition('anchormenu'));

class GD_Template_Processor_AnchorMenus extends GD_Template_Processor_AnchorMenusBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ANCHORMENU
		);
	}

	function get_item_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORMENU:
				
				return 'btn btn-default btn-block';
		}
	
		return parent::get_item_class($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_AnchorMenus();