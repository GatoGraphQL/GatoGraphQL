<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_LOCATIONSMAP', PoP_ServerUtils::get_template_definition('block-locationsmap'));
define ('GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP', PoP_ServerUtils::get_template_definition('block-staticlocationsmap'));

class GD_Template_Processor_LocationsMapBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_LOCATIONSMAP,
			GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP,
		);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LOCATIONSMAP:

				$ret[] = GD_TEMPLATE_MAP_DIV;
				$ret[] = GD_TEMPLATE_SCROLL_LOCATIONS_MAP;
				$ret[] = GD_TEMPLATE_MAP_SCRIPT_DRAWMARKERS;
				$ret[] = GD_TEMPLATE_MAP_SCRIPT_RESETMARKERS;
				break;

			case GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP:

				$ret[] = GD_TEMPLATE_MAP_DIV;
				break;
		}
	
		return $ret;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LOCATIONSMAP:

				return GD_DATALOADER_EDITLOCATION;
		}
		
		return parent::get_dataloader($template_id);
	}

	function get_dataload_source($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_STATICLOCATIONSMAP:

				// So it can be intercepted
				return get_permalink(POP_EM_POPPROCESSORS_PAGE_LOCATIONSMAP);
		}
		
		return parent::get_dataload_source($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LOCATIONSMAP:

				// No need to show the locations list, only the map will do
				$this->append_att(GD_TEMPLATE_SCROLL_LOCATIONS_MAP, $atts, 'class', 'hidden');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
	
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationsMapBlocks();