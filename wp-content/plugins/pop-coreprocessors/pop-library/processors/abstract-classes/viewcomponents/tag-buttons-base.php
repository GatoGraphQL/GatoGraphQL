<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TagViewComponentButtonsBase extends GD_Template_Processor_ViewComponentButtonsBase {

	function get_header_template($template_id) {
		
		if ($this->header_show_url($template_id)) {

			return GD_TEMPLATE_VIEWCOMPONENT_HEADER_TAG_URL;
		}

		return GD_TEMPLATE_VIEWCOMPONENT_HEADER_TAG;
	}

	function get_itemobject_params($template_id) {

		$ret = parent::get_itemobject_params($template_id);

		$ret['data-target-title'] = 'name';
		$ret['data-target-url'] = 'url';
		
		return $ret;
	}
}