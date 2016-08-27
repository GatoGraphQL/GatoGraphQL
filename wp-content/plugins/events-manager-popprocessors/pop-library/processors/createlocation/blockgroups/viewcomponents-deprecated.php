<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_CREATELOCATION_MODAL', PoP_ServerUtils::get_template_definition('blockgroup-createlocation-modal'));

/**
 * DEPRECATED. Use the Create Location Block in the MODALS pageSection instead
 */
class GD_Template_Processor_CreateLocationModalBlockGroups extends GD_Template_Processor_CreateLocationFormModalViewComponentBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_CREATELOCATION_MODAL
		);
	}

	function get_header_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CREATELOCATION_MODAL:

				return __('Add Location', 'em-popprocessors');
		}

		return parent::get_header_title($template_id);
	}
	function get_icon($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_CREATELOCATION_MODAL:

				return 'fa-map-marker';
		}

		return parent::get_icon($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateLocationModalBlockGroups();
