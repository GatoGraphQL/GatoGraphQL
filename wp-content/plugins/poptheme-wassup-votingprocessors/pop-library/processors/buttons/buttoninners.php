<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-opinionatedvote-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_OPINIONATEDVOTE_UPDATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-opinionatedvote-update'));
define ('GD_TEMPLATE_LAZYBUTTONINNER_OPINIONATEDVOTE_CREATEORUPDATE', PoP_ServerUtils::get_template_definition('lazybuttoninner-opinionatedvote-createorupdate'));
define ('GD_TEMPLATE_BUTTONINNER_POSTSTANCE_PRO', PoP_ServerUtils::get_template_definition('buttoninner-poststance-pro'));
define ('GD_TEMPLATE_BUTTONINNER_POSTSTANCE_NEUTRAL', PoP_ServerUtils::get_template_definition('buttoninner-poststance-neutral'));
define ('GD_TEMPLATE_BUTTONINNER_POSTSTANCE_AGAINST', PoP_ServerUtils::get_template_definition('buttoninner-poststance-against'));

class VotingProcessors_Template_Processor_ButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_OPINIONATEDVOTE_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_LAZYBUTTONINNER_OPINIONATEDVOTE_CREATEORUPDATE,
			GD_TEMPLATE_BUTTONINNER_POSTSTANCE_PRO,
			GD_TEMPLATE_BUTTONINNER_POSTSTANCE_NEUTRAL,
			GD_TEMPLATE_BUTTONINNER_POSTSTANCE_AGAINST,
		);
	}

	function get_fontawesome($template_id, $atts) {

		$icons = array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_OPINIONATEDVOTE_CREATE => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOPINIONATEDVOTE,
			GD_TEMPLATE_LAZYBUTTONINNER_OPINIONATEDVOTE_CREATEORUPDATE => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_ADDOPINIONATEDVOTE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_OPINIONATEDVOTE_UPDATE => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_EDITOPINIONATEDVOTE,
			GD_TEMPLATE_BUTTONINNER_POSTSTANCE_PRO => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_PRO,
			GD_TEMPLATE_BUTTONINNER_POSTSTANCE_NEUTRAL => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_NEUTRAL,
			GD_TEMPLATE_BUTTONINNER_POSTSTANCE_AGAINST => POPTHEME_WASSUP_VOTINGPROCESSORS_PAGE_OPINIONATEDVOTES_AGAINST,
		);
		if ($icon = $icons[$template_id]) {

			return 'fa-fw '.gd_navigation_menu_item($icon, false);
		}
		
		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		// Allow Events to have a different title
		$opinionatedvote = sprintf(
			__('%s, %s', 'poptheme-wassup-votingprocessors'),//__('%s, what do you think about TPP?', 'poptheme-wassup-votingprocessors'),
			VotingProcessors_Template_Processor_ButtonUtils::get_fullview_addopinionatedvote_title(),
			PoPTheme_Wassup_VotingProcessors_Utils::get_whatisyourvote_title('lc')
		);
		$titles = array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_OPINIONATEDVOTE_CREATE => $opinionatedvote,
			GD_TEMPLATE_LAZYBUTTONINNER_OPINIONATEDVOTE_CREATEORUPDATE => $opinionatedvote.GD_CONSTANT_LOADING_SPINNER,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_OPINIONATEDVOTE_UPDATE => sprintf(
				__('Edit your corresponding %s', 'poptheme-wassup-votingprocessors'),
				gd_get_categoryname(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES)//__('Thought on TPP', 'poptheme-wassup-votingprocessors')
			),
			GD_TEMPLATE_BUTTONINNER_POSTSTANCE_PRO => __('Pro', 'poptheme-wassup-votingprocessors'),
			GD_TEMPLATE_BUTTONINNER_POSTSTANCE_NEUTRAL => __('Neutral', 'poptheme-wassup-votingprocessors'),
			GD_TEMPLATE_BUTTONINNER_POSTSTANCE_AGAINST => __('Against', 'poptheme-wassup-votingprocessors'),
		);
		if ($title = $titles[$template_id]) {

			return $title;
		}
		
		return parent::get_btn_title($template_id);
	}

	function get_text_field($template_id, $atts) {
		
		switch ($template_id) {

			case GD_TEMPLATE_BUTTONINNER_POSTSTANCE_PRO:
		
				return 'stance-pro-count';

			case GD_TEMPLATE_BUTTONINNER_POSTSTANCE_NEUTRAL:
		
				return 'stance-neutral-count';

			case GD_TEMPLATE_BUTTONINNER_POSTSTANCE_AGAINST:
		
				return 'stance-against-count';
		}
		
		return parent::get_text_field($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_ButtonInners();