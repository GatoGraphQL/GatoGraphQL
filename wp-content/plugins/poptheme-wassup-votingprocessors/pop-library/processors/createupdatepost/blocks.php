<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE', PoP_ServerUtils::get_template_definition('block-opinionatedvote-update'));
define ('GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('block-opinionatedvote-create'));
define ('GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE', PoP_ServerUtils::get_template_definition('block-opinionatedvote-createorupdate'));
define ('GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE', PoP_ServerUtils::get_template_definition('block-singlepostopinionatedvote-createorupdate'));

class VotingProcessors_Template_Processor_CreateUpdatePostBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE,
			GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE,
		);
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE:

				return GD_TEMPLATE_CONTROLGROUP_CREATEPOST;

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE:
			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE:
			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				return GD_TEMPLATE_CONTROLGROUP_EDITPOST;
		}
		
		return parent::get_controlgroup_top($template_id);
	}
	

	protected function get_messagefeedback($template_id) {

		$feedbacks = array(
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE => GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE => GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE => GD_TEMPLATE_MESSAGEFEEDBACK_OPINIONATEDVOTE_CREATE,
		);
	
		if ($feedback = $feedbacks[$template_id]) {

			return $feedback;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		$block_inners = array(
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE => GD_TEMPLATE_FORM_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE => GD_TEMPLATE_FORM_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE => GD_TEMPLATE_FORM_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE => GD_TEMPLATE_FORM_OPINIONATEDVOTE_UPDATE,
		);
		if ($block_inner = $block_inners[$template_id]) {

			$ret[] = $block_inner;
		}
	
		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE:

				$this->add_jsmethod($ret, 'formCreatePostBlock');
				break;
		
			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE:

				$this->add_jsmethod($ret, 'destroyPageOnUserLoggedOut');
				$this->add_jsmethod($ret, 'refetchBlockOnUserLoggedIn');
				break;

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE:
			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				$this->add_jsmethod($ret, 'nonendingRefetchBlockOnUserLoggedIn');
				$this->add_jsmethod($ret, 'resetOnUserLogout');
				break;
		}
		
		return $ret;
	}

	function get_dataloader($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE:

				return GD_DATALOADER_EDITPOST;

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE:
			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				return GD_DATALOADER_MAYBEEDITPOSTOPINIONATEDVOTE;

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE:

				return GD_DATALOADER_NOPOSTS;
		}

		return parent::get_dataloader($template_id);
	}

	protected function get_iohandler($template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE:

				return GD_DATALOAD_IOHANDLER_ADDPOST;
					
			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE:
				
				return GD_DATALOAD_IOHANDLER_EDITPOST;
			
			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE:

				if ($vars['fetching-json-data']) {

					return GD_DATALOAD_IOHANDLER_ADDOREDITPOSTOPINIONATEDVOTE;
				}
				break;

			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				if ($vars['fetching-json-data']) {

					return GD_DATALOAD_IOHANDLER_ADDOREDITPOSTOPINIONATEDVOTE;
				}
				return GD_DATALOAD_IOHANDLER_ADDSINGLEPOSTOPINIONATEDVOTE;
		}
		
		return parent::get_iohandler($template_id);
	}

	protected function get_blocksections_classes($template_id) {

		$ret = parent::get_blocksections_classes($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				$ret['blocksection-inners'] = 'well';
				break;
		}

		return $ret;
	}

	protected function get_block_page($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				if ($page = $this->get_block_page(GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE)) {

					return $page;
				}
				break;	
		}
	
		return parent::get_block_page($template_id);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:
			
				// Allow Events to have a different title
				$title = sprintf(
					__('%s...', 'poptheme-wassup-votingprocessors'),
					VotingProcessors_Template_Processor_ButtonUtils::get_singlepost_addopinionatedvote_title()
				);
				return sprintf(
					'<small>%s</small>',
					gd_navigation_menu_item(POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES, true).$title
				);
		}
	
		return parent::get_title($template_id);
	}

	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE:
			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				$ret['processblock-ifhasdata'] = true;
				$ret['loadcontent-showdisabledlayer'] = true;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$vars = GD_TemplateManager_Utils::get_vars();

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE:
			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE:
			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				$updates = array(
					GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE,
					GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE,
					GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE,
				);
				$class = 'pop-block-createupdatepost ';
				if (in_array($template_id, $updates)) {
					$class .= 'pop-block-update-post';
				}
				else {
					$class .= 'pop-block-create-post';
				}
				$this->append_att($template_id, $atts, 'class', $class);
				$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', __('Submitting...', 'poptheme-wassup-votingprocessors'));
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE:
			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				$this->append_att($template_id, $atts, 'class', 'pop-blockopinionatedvote-createorupdate');

				// Load only from the client
				if (!$vars['fetching-json-data']) {
					$this->add_att($template_id, $atts, 'content-loaded', false);
				}
				break;
		}

		switch ($template_id) {

			// Make it horizontal
			case GD_TEMPLATE_BLOCK_SINGLEPOSTOPINIONATEDVOTE_CREATEORUPDATE:

				// Do not show in the quickview
				$this->append_att($template_id, $atts, 'class', 'pop-singlepostopinionatedvote pop-hidden-quickview');
				$this->add_att(GD_TEMPLATE_FORM_OPINIONATEDVOTE_UPDATE, $atts, 'horizontal', true);

				// Initialize the typeahead with the value of the single post
				global $post;
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES, $atts, 'selected', array($post->ID));
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_UPDATE:
			case GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATE:

				$this->append_att($template_id, $atts, 'class', 'addons-nocontrols');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CreateUpdatePostBlocks();