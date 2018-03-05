<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL', PoP_TemplateIDUtils::get_template_definition('blockgroup-sharebyemail-modal'));

class GD_Template_Processor_GFModalBlockGroups extends GD_Template_Processor_FormModalViewComponentBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
		);
	}

	function get_blockgroup_blocks($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL:

				return array(
					GD_TEMPLATE_BLOCK_SHAREBYEMAIL
				);
		}

		return parent::get_blockgroup_blocks($template_id);
	}

	function get_header_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL:

				return __('Share by email:', 'pop-genericforms');
		}

		return parent::get_header_title($template_id);
	}

	function get_icon($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL:

				return 'fa-share';
		}

		return parent::get_icon($template_id);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {
	
		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL:

				// Do not show the labels in the form
				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');
				break;
		}
		
		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_GFModalBlockGroups();
