<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL', PoP_ServerUtils::get_template_definition('blockgroup-sharebyemail-modal'));

class GD_Template_Processor_GFModalBlockGroups extends GD_Template_Processor_FormModalViewComponentBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_BLOCKGROUP_CONTACTUS_MODAL,
			// GD_TEMPLATE_BLOCKGROUP_CONTACTUSER_MODAL,
			GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
			// GD_TEMPLATE_BLOCKGROUP_VOLUNTEER_MODAL
		);
	}

	function get_blockgroup_blocks($template_id) {

		switch ($template_id) {

			// case GD_TEMPLATE_BLOCKGROUP_CONTACTUS_MODAL:

			// 	return array(
			// 		GD_TEMPLATE_BLOCK_CONTACTUS
			// 	);

			// case GD_TEMPLATE_BLOCKGROUP_CONTACTUSER_MODAL:

			// 	return array(
			// 		GD_TEMPLATE_BLOCK_CONTACTUSER
			// 	);

			case GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL:

				return array(
					GD_TEMPLATE_BLOCK_SHAREBYEMAIL
				);

			// case GD_TEMPLATE_BLOCKGROUP_VOLUNTEER_MODAL:

			// 	return array(
			// 		GD_TEMPLATE_BLOCK_VOLUNTEER
			// 	);
		}

		return parent::get_blockgroup_blocks($template_id);
	}

	function get_header_title($template_id) {

		switch ($template_id) {

			// case GD_TEMPLATE_BLOCKGROUP_CONTACTUS_MODAL:

			// 	return __('Contact us', 'poptheme-wassup');

			// case GD_TEMPLATE_BLOCKGROUP_CONTACTUSER_MODAL:

			// 	return __('Send message to:', 'poptheme-wassup');

			case GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL:

				return __('Share by email:', 'poptheme-wassup');

			// case GD_TEMPLATE_BLOCKGROUP_VOLUNTEER_MODAL:

			// 	return __('Volunteer for:', 'poptheme-wassup');
		}

		return parent::get_header_title($template_id);
	}

	function get_icon($template_id) {

		switch ($template_id) {

			// case GD_TEMPLATE_BLOCKGROUP_CONTACTUS_MODAL:

			// 	return 'fa-envelope-o';

			// case GD_TEMPLATE_BLOCKGROUP_CONTACTUSER_MODAL:

			// 	return 'fa-envelope';

			case GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL:

				return 'fa-share';

			// case GD_TEMPLATE_BLOCKGROUP_VOLUNTEER_MODAL:

			// 	return 'fa-leaf';
		}

		return parent::get_icon($template_id);
	}



	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {
	
		switch ($blockgroup) {

			// case GD_TEMPLATE_BLOCKGROUP_CONTACTUS_MODAL:
			// case GD_TEMPLATE_BLOCKGROUP_CONTACTUSER_MODAL:
			case GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL:
			// case GD_TEMPLATE_BLOCKGROUP_VOLUNTEER_MODAL:

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
