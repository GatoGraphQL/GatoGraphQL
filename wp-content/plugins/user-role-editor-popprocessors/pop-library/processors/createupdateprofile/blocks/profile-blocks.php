<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_MYCOMMUNITIES_UPDATE', PoP_TemplateIDUtils::get_template_definition('block-mycommunities-update'));
define ('GD_TEMPLATE_BLOCK_INVITENEWMEMBERS', PoP_TemplateIDUtils::get_template_definition('block-invitemembers'));
define ('GD_TEMPLATE_BLOCK_EDITMEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('block-editmembership'));

class GD_URE_Template_Processor_ProfileBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(			
			GD_TEMPLATE_BLOCK_MYCOMMUNITIES_UPDATE,
			GD_TEMPLATE_BLOCK_INVITENEWMEMBERS,
			GD_TEMPLATE_BLOCK_EDITMEMBERSHIP,
		);
	}

	protected function get_description($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_INVITENEWMEMBERS:

				return sprintf(
					'<div class="alert alert-info"><p>%s</p><ul><li>%s</li><li>%s</li></ul></div>',
					sprintf(
						__('Send an invite for all your physical members to also become your members on <em><strong>%s</strong></em>:', 'ure-popprocessors'),
						get_bloginfo('name')
					),
					__('<strong>Become a community: </strong>all their content in the website will also show up under your profile'),
					__('<strong>Keep them engaged: </strong>all members will receive notifications of any activity by any member or your Organization')
				);
		}
		
		return parent::get_description($template_id, $atts);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYCOMMUNITIES_UPDATE:

				$ret[] = GD_TEMPLATE_FORM_MYCOMMUNITIES_UPDATE;
				break;

			case GD_TEMPLATE_BLOCK_INVITENEWMEMBERS:

				// $ret[] = GD_TEMPLATE_FORM_INVITENEWMEMBERS;
				$ret[] = GD_TEMPLATE_FORM_INVITENEWUSERS;
				break;

			case GD_TEMPLATE_BLOCK_EDITMEMBERSHIP:

				$ret[] = GD_URE_TEMPLATE_CONTENT_MEMBER;
				$ret[] = GD_TEMPLATE_FORM_EDITMEMBERSHIP;
				break;
		}
	
		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYCOMMUNITIES_UPDATE:

				$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
				$this->add_jsmethod($ret, 'refetchBlockOnUserLoggedIn');
				break;
		
			case GD_TEMPLATE_BLOCK_INVITENEWMEMBERS:

				$this->add_jsmethod($ret, 'destroyPageOnUserNoRole');
				break;
		
			case GD_TEMPLATE_BLOCK_EDITMEMBERSHIP:

				$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
				$this->add_jsmethod($ret, 'refetchBlockOnUserLoggedIn');
				$this->add_jsmethod($ret, 'destroyPageOnUserNoRole');
				break;
		}

		return $ret;
	}

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYCOMMUNITIES_UPDATE:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_MYCOMMUNITIES;

			case GD_TEMPLATE_BLOCK_INVITENEWMEMBERS:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_INVITENEWMEMBERS;
		
			case GD_TEMPLATE_BLOCK_EDITMEMBERSHIP:
		
				return GD_TEMPLATE_MESSAGEFEEDBACK_EDITMEMBERSHIP;
		}

		return parent::get_messagefeedback($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYCOMMUNITIES_UPDATE:

				$submitting = __('Submitting...', 'ure-popprocessors');
				$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', $submitting);
				break;
		
			case GD_TEMPLATE_BLOCK_INVITENEWMEMBERS:
			case GD_TEMPLATE_BLOCK_EDITMEMBERSHIP:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-neededrole' => GD_URE_ROLE_COMMUNITY
				));
				$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', __('Sending...', 'ure-popprocessors'));
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYCOMMUNITIES_UPDATE:
			
				return GD_DATALOADER_USERSINGLEEDIT;

			case GD_TEMPLATE_BLOCK_EDITMEMBERSHIP:
			
				return GD_DATALOADER_EDITUSER;
		}
	
		return parent::get_dataloader($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_MYCOMMUNITIES_UPDATE:
			case GD_TEMPLATE_BLOCK_INVITENEWMEMBERS:

				return GD_DATALOAD_IOHANDLER_FORM;

			case GD_TEMPLATE_BLOCK_EDITMEMBERSHIP:

				return GD_DATALOAD_IOHANDLER_EDITMEMBERSHIP;
		}

		return parent::get_iohandler($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_ProfileBlocks();