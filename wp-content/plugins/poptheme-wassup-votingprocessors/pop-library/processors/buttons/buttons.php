<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTON_OPINIONATEDVOTEEDIT', PoP_ServerUtils::get_template_definition('button-opinionatedvoteedit'));
define ('GD_TEMPLATE_BUTTON_OPINIONATEDVOTEVIEW', PoP_ServerUtils::get_template_definition('button-opinionatedvoteview'));
define ('GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_PRO', PoP_ServerUtils::get_template_definition('button-postopinionatedvotes-pro'));
define ('GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_NEUTRAL', PoP_ServerUtils::get_template_definition('button-postopinionatedvotes-neutral'));
define ('GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_AGAINST', PoP_ServerUtils::get_template_definition('button-postopinionatedvotes-against'));

class VotingProcessors_Template_Processor_Buttons extends GD_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTEEDIT,
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTEVIEW,
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_PRO,
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_NEUTRAL,
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_AGAINST,
		);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTEEDIT => GD_TEMPLATE_BUTTONINNER_POSTEDIT,
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTEVIEW => GD_TEMPLATE_BUTTONINNER_POSTVIEW,
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_PRO => GD_TEMPLATE_BUTTONINNER_POSTSTANCE_PRO,
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_NEUTRAL => GD_TEMPLATE_BUTTONINNER_POSTSTANCE_NEUTRAL,
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_AGAINST => GD_TEMPLATE_BUTTONINNER_POSTSTANCE_AGAINST,
		);
		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_url_field($template_id) {
		
		$fields = array(
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTEEDIT => 'edit-url',
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_PRO => 'postopinionatedvotes-pro-url',
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_NEUTRAL => 'postopinionatedvotes-neutral-url',
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_AGAINST => 'postopinionatedvotes-against-url',
		);
		if ($field = $fields[$template_id]) {

			return $field;
		}

		return parent::get_url_field($template_id);
	}

	function get_title($template_id) {
		
		$titles = array(
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTEEDIT => __('Edit', 'poptheme-wassup-votingprocessors'),
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTEVIEW => __('View', 'poptheme-wassup-votingprocessors'),
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_PRO => __('Pro', 'poptheme-wassup-votingprocessors'),
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_NEUTRAL => __('Neutral', 'poptheme-wassup-votingprocessors'),
			GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_AGAINST => __('Against', 'poptheme-wassup-votingprocessors'),
		);
		if ($title = $titles[$template_id]) {

			return $title;
		}
		
		return parent::get_title($template_id);
	}

	function get_linktarget($template_id, $atts) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTEEDIT;

				return GD_URLPARAM_TARGET_ADDONS;

			case GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_NEUTRAL:
			case GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_AGAINST:
		
				return GD_URLPARAM_TARGET_QUICKVIEW;
		}
		
		return parent::get_linktarget($template_id, $atts);
	}

	function get_btn_class($template_id, $atts) {

		$ret = parent::get_btn_class($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTEVIEW:
			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTEEDIT:

				$ret .= ' btn btn-xs btn-default';
				break;

			case GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_PRO:
			case GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_NEUTRAL:
			case GD_TEMPLATE_BUTTON_POSTOPINIONATEDVOTES_AGAINST:

				$ret .= ' btn btn-link';
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_Buttons();