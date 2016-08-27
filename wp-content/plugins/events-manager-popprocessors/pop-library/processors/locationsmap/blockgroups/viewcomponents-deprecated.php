<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_LOCATIONSMAP_MODAL', PoP_ServerUtils::get_template_definition('blockgroup-locationsmap-modal'));

/**
 * DEPRECATED. Use the Locations Map Block in the MODALS pageSection instead
 */
class GD_Template_Processor_LocationsMapModalBlockGroups extends GD_Template_Processor_LocationsModalViewComponentBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_LOCATIONSMAP_MODAL
		);
	}

	function get_blockgroup_blocks($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_LOCATIONSMAP_MODAL:

				return array(
					GD_TEMPLATE_BLOCK_LOCATIONSMAP
				);
		}

		return parent::get_blockgroup_blocks($template_id);
	}

	function get_header_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_LOCATIONSMAP_MODAL:

				return __('Location(s) for:', 'em-popprocessors');
		}

		return parent::get_header_title($template_id);
	}
	function get_icon($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_LOCATIONSMAP_MODAL:

				return 'fa-map-marker';
		}

		return parent::get_icon($template_id);
	}

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);
		
		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCKGROUP_LOCATIONSMAP_MODAL:

				$this->add_jsmethod($ret, 'mapModal', $this->get_type($template_id));
				break;
		}

		return $ret;
	}

	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationsMapModalBlockGroups();
