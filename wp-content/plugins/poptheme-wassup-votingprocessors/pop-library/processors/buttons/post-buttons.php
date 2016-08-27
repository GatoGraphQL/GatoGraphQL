<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('postbutton-opinionatedvote-create'));
define ('GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_UPDATE', PoP_ServerUtils::get_template_definition('postbutton-opinionatedvote-update'));
define ('GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE', PoP_ServerUtils::get_template_definition('lazypostbutton-opinionatedvote-createorupdate'));

class VotingProcessors_Template_Processor_PostButtons extends GD_Custom_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE,
		);
	}

	function get_data_fields($template_id, $atts) {
	
		$ret = parent::get_data_fields($template_id, $atts);

		switch ($template_id) {
				
			case GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE:
			
				$ret[] = 'createopinionatedvotebutton-lazy';
				break;
		}
		
		return $ret;
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_OPINIONATEDVOTE_CREATE,
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_UPDATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE => GD_TEMPLATE_LAZYBUTTONINNER_OPINIONATEDVOTE_CREATEORUPDATE,
		);
		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_selectabletypeahead_template($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_UPDATE:
			case GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE:

				return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES;
		}

		return parent::get_selectabletypeahead_template($template_id);
	}

	function get_linktarget($template_id, $atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_UPDATE:
			case GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE:

				return GD_URLPARAM_TARGET_ADDONS;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {
					
			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_UPDATE:
			case GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE:

				$ret .= ' btn btn-link';
				break;
		}

		switch ($template_id) {
					
			case GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE:

				$ret .= ' disabled';
				break;
		}

		return $ret;
	}

	function get_title($template_id) {

		// Allow Events to have a different title
		$opinionatedvote = sprintf(
			// __('%s, what do you think about TPP?', 'poptheme-wassup-votingprocessors'),
			__('%s, %s', 'poptheme-wassup-votingprocessors'),
			PoPTheme_Wassup_VotingProcessors_Utils::get_whatisyourvote_title('lc'),
			VotingProcessors_Template_Processor_ButtonUtils::get_fullview_addopinionatedvote_title()
		);
		$titles = array(
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATE => $opinionatedvote,
			GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE => $opinionatedvote,
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_UPDATE => sprintf(
				__('Edit your corresponding %s', 'poptheme-wassup-votingprocessors'),
				gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES)//__('Thought on TPP', 'poptheme-wassup-votingprocessors')
			),
		);
		if ($title = $titles[$template_id]) {

			return $title;
		}
		
		return parent::get_title($template_id);
	}

	function get_url_field($template_id) {

		$fields = array(
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATE => 'addopinionatedvote-url',
			GD_TEMPLATE_LAZYBUTTON_OPINIONATEDVOTE_CREATEORUPDATE => 'addopinionatedvote-url',
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_UPDATE => 'editopinionatedvote-url',
		);
		if ($field = $fields[$template_id]) {

			return $field;
		}
		
		return parent::get_url_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_PostButtons();