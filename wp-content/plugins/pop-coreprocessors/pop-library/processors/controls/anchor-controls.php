<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS', PoP_ServerUtils::get_template_definition('anchorcontrol-toggleoptionalfields'));
define ('GD_TEMPLATE_ANCHORCONTROL_EXPANDCOLLAPSIBLE', PoP_ServerUtils::get_template_definition('anchorcontrol-expandcollapsible'));
define ('GD_TEMPLATE_ANCHORCONTROL_FILTERTOGGLE', PoP_ServerUtils::get_template_definition('anchorcontrol-filtertoggle'));
define ('GD_TEMPLATE_ANCHORCONTROL_CURRENTURL', PoP_ServerUtils::get_template_definition('anchorcontrol-currenturl'));
define ('GD_TEMPLATE_ANCHORCONTROL_SUBMENUTOGGLE_XS', PoP_ServerUtils::get_template_definition('anchorcontrol-submenutoggle-xs'));
define ('GD_TEMPLATE_ANCHORCONTROL_PRINT', PoP_ServerUtils::get_template_definition('anchorcontrol-print'));
define ('GD_TEMPLATE_ANCHORCONTROL_EMBED', PoP_ServerUtils::get_template_definition('anchorcontrol-embed'));
define ('GD_TEMPLATE_ANCHORCONTROL_COPYSEARCHURL', PoP_ServerUtils::get_template_definition('anchorcontrol-copysearchurl'));
define ('GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL', PoP_ServerUtils::get_template_definition('anchorcontrol-sharebyemail'));
define ('GD_TEMPLATE_ANCHORCONTROL_INVITENEWUSERS', PoP_ServerUtils::get_template_definition('anchorcontrol-invitenewusers'));
define ('GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGE', PoP_ServerUtils::get_template_definition('anchorcontrol-closepage'));
define ('GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBTN', PoP_ServerUtils::get_template_definition('anchorcontrol-closepagebtn'));
define ('GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBOTTOM', PoP_ServerUtils::get_template_definition('anchorcontrol-closepagebottom'));
define ('GD_TEMPLATE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO', PoP_ServerUtils::get_template_definition('anchorcontrol-togglequickviewinfo'));
define ('GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO', PoP_ServerUtils::get_template_definition('anchorcontrol-togglesideinfo'));
define ('GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS', PoP_ServerUtils::get_template_definition('anchorcontrol-togglesideinfoxs'));
define ('GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK', PoP_ServerUtils::get_template_definition('anchorcontrol-togglesideinfoxs-back'));
define ('GD_TEMPLATE_ANCHORCONTROL_SHARE_FACEBOOK', PoP_ServerUtils::get_template_definition('anchorcontrol-share-facebook'));
define ('GD_TEMPLATE_ANCHORCONTROL_SHARE_TWITTER', PoP_ServerUtils::get_template_definition('anchorcontrol-share-twitter'));
define ('GD_TEMPLATE_ANCHORCONTROL_SHARE_LINKEDIN', PoP_ServerUtils::get_template_definition('anchorcontrol-share-linkedin'));

class GD_Template_Processor_AnchorControls extends GD_Template_Processor_AnchorControlsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS,
			GD_TEMPLATE_ANCHORCONTROL_EXPANDCOLLAPSIBLE,
			GD_TEMPLATE_ANCHORCONTROL_FILTERTOGGLE,
			GD_TEMPLATE_ANCHORCONTROL_CURRENTURL,
			GD_TEMPLATE_ANCHORCONTROL_SUBMENUTOGGLE_XS,
			GD_TEMPLATE_ANCHORCONTROL_PRINT,
			GD_TEMPLATE_ANCHORCONTROL_EMBED,
			GD_TEMPLATE_ANCHORCONTROL_COPYSEARCHURL,
			GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL,
			GD_TEMPLATE_ANCHORCONTROL_INVITENEWUSERS,
			GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGE,
			GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBTN,
			GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBOTTOM,
			GD_TEMPLATE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO,
			GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO,
			GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS,
			GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK,
			GD_TEMPLATE_ANCHORCONTROL_SHARE_FACEBOOK,
			GD_TEMPLATE_ANCHORCONTROL_SHARE_TWITTER,
			GD_TEMPLATE_ANCHORCONTROL_SHARE_LINKEDIN,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
			
				return __('Toggle compact/full form', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
			
				return __('Expand', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_FILTERTOGGLE:
			
				return __('Filter', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_CURRENTURL:

				return __('Permalink', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_PRINT:
			
				return __('Print', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_EMBED:

				return __('Embed', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_COPYSEARCHURL:

				return __('Copy Search URL', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL:

				return __('Share by email', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWUSERS:

				return __('Invite your friends to join!', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGE:
			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBTN:
			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBOTTOM:

				return __('Close', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO:
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO:
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS:

				return __('Toggle sidebar', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK:

				return __('Go back', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_SHARE_FACEBOOK:

				return __('Facebook', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_SHARE_TWITTER:

				return __('Twitter', 'pop-coreprocessors');

			case GD_TEMPLATE_ANCHORCONTROL_SHARE_LINKEDIN:

				return __('LinkedIn', 'pop-coreprocessors');

		}

		return parent::get_label($template_id, $atts);
	}
	function get_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_FILTERTOGGLE:
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK:
			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBTN:

				return null;
		}

		return parent::get_text($template_id, $atts);
	}
	function get_icon($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_SUBMENUTOGGLE_XS:

				return 'glyphicon-menu-hamburger';

			case GD_TEMPLATE_ANCHORCONTROL_PRINT:
			
				return 'glyphicon-print';

			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGE:
			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBTN:
			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBOTTOM:

				return 'glyphicon-remove';

			case GD_TEMPLATE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO:
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO:
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS:

				return 'glyphicon-arrow-right';
			
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK:

				return 'glyphicon-arrow-left';
		}

		return parent::get_icon($template_id);
	}
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
			case GD_TEMPLATE_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
			
				return 'fa-arrows-v';

			case GD_TEMPLATE_ANCHORCONTROL_FILTERTOGGLE:
			
				return 'fa-filter';

			case GD_TEMPLATE_ANCHORCONTROL_CURRENTURL:

				return 'fa-link';

			case GD_TEMPLATE_ANCHORCONTROL_EMBED:

				return 'fa-code';

			case GD_TEMPLATE_ANCHORCONTROL_COPYSEARCHURL:

				return 'fa-link';

			case GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL:

				return 'fa-envelope';
		
			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWUSERS:

				return 'fa-user-plus';
		
			case GD_TEMPLATE_ANCHORCONTROL_SHARE_FACEBOOK:

				return 'fa-facebook';

			case GD_TEMPLATE_ANCHORCONTROL_SHARE_TWITTER:

				return 'fa-twitter';

			case GD_TEMPLATE_ANCHORCONTROL_SHARE_LINKEDIN:

				return 'fa-linkedin';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	function get_href($template_id, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:
			case GD_TEMPLATE_ANCHORCONTROL_EXPANDCOLLAPSIBLE:

				$block_id = $atts['block-id'];
				return '#'.$block_id.' .collapse';

			case GD_TEMPLATE_ANCHORCONTROL_FILTERTOGGLE:

				// Comment Leo: calling ->get_frontend_id( will possibly not work inside of a replicated pageSection, since this ID is hardcoded in the settings
				// however the ID of the target will change. To deal with this, possibly use a jQuery selector other than $('#').
				// Currently this is not a problem because we don't have any pageSection to be replicated that needs a filter (nothing listing content, only to create, eg: Add Project)
				// $filter_id = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FILTER)->get_frontend_id(GD_TEMPLATE_FILTER, $atts);
				// return '#'.$filter_id;

				// The Filter is set in the atts
				if ($filter = $atts['filter']) {
					$filter_id = $gd_template_processor_manager->get_processor($filter)->get_frontend_id($filter, $atts);
					return '#'.$filter_id;
				}
				return null;

			case GD_TEMPLATE_ANCHORCONTROL_CURRENTURL:

				return GD_TemplateManager_Utils::get_current_url();

			case GD_TEMPLATE_ANCHORCONTROL_SUBMENUTOGGLE_XS:

				// The Submenu is set in the atts
				if ($submenu = $atts['submenu']) {
					$submenu_id = $gd_template_processor_manager->get_processor($submenu)->get_frontend_id($submenu, $atts);
					return '#'.$submenu_id.'-xs';
				}
				return null;

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWUSERS:

				return get_permalink(POP_COREPROCESSORS_PAGE_INVITENEWUSERS);

			case GD_TEMPLATE_ANCHORCONTROL_EMBED:
			case GD_TEMPLATE_ANCHORCONTROL_COPYSEARCHURL:
			case GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL:

				$modals = array(
					GD_TEMPLATE_ANCHORCONTROL_EMBED => GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL,
					GD_TEMPLATE_ANCHORCONTROL_COPYSEARCHURL => GD_TEMPLATE_BLOCKGROUP_COPYSEARCHURL_MODAL,
					GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL => GD_TEMPLATE_BLOCKGROUP_SHAREBYEMAIL_MODAL,
				);
				$modal = $modals[$template_id];
				return '#'.$gd_template_processor_manager->get_processor($modal)->get_frontend_id($modal, $atts).'_modal';
		}

		return parent::get_href($template_id, $atts);
	}

	function get_target($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWUSERS:

				return GD_URLPARAM_TARGET_MODALS;
		
			case GD_TEMPLATE_ANCHORCONTROL_CURRENTURL:

				return GD_URLPARAM_TARGET_MAIN;
		}

		return parent::get_target($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_TOGGLEOPTIONALFIELDS:

				$this->append_att($template_id, $atts, 'class', 'btn btn-primary');
				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'collapse'
				));
				break;

			case GD_TEMPLATE_ANCHORCONTROL_EXPANDCOLLAPSIBLE:
			case GD_TEMPLATE_ANCHORCONTROL_FILTERTOGGLE:
			case GD_TEMPLATE_ANCHORCONTROL_SUBMENUTOGGLE_XS:
			
				$this->append_att($template_id, $atts, 'class', 'btn btn-compact btn-link');
				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'collapse'
				));
				break;

			case GD_TEMPLATE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'offcanvas-toggle',
					'data-target' => '#'.GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWSIDEINFO,
				));
				break;

			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO:
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS:
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK:

				$mode = '';
				$classs = '';
				$xs = array(
					GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS,
					GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK
				);
				$tablet = array(
					GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO,
				);
				if (in_array($template_id, $xs)) {
					$mode = 'xs';
					$classs = 'hidden-sm hidden-md hidden-lg';
				}
				elseif (in_array($template_id, $tablet)) {
					$classs = 'hidden-xs';
				}
				
				$back = array(
					GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK
				);
				if (in_array($template_id, $back)) {
					$classs .= ' btn btn-lg btn-link';
				}


				$this->append_att($template_id, $atts, 'class', $classs);
				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'offcanvas-toggle',
					'data-target' => '#'.GD_TEMPLATEID_PAGESECTIONID_SIDEINFO,
					'data-mode' => $mode,
				));
				break;

			case GD_TEMPLATE_ANCHORCONTROL_PRINT:
			
				$this->merge_att($template_id, $atts, 'params', array(
					'data-blocktarget' => $this->get_att($template_id, $atts, 'block-target')
				));
				break;

			case GD_TEMPLATE_ANCHORCONTROL_SHARE_FACEBOOK:
			case GD_TEMPLATE_ANCHORCONTROL_SHARE_TWITTER:
			case GD_TEMPLATE_ANCHORCONTROL_SHARE_LINKEDIN:

				$providers = array(
					GD_TEMPLATE_ANCHORCONTROL_SHARE_FACEBOOK => GD_SOCIALMEDIA_PROVIDER_FACEBOOK,
					GD_TEMPLATE_ANCHORCONTROL_SHARE_TWITTER => GD_SOCIALMEDIA_PROVIDER_TWITTER,
					GD_TEMPLATE_ANCHORCONTROL_SHARE_LINKEDIN => GD_SOCIALMEDIA_PROVIDER_LINKEDIN,
				);
				$this->merge_att($template_id, $atts, 'params', array(
					'data-provider' => $providers[$template_id],
					'data-blocktarget' => $this->get_att($template_id, $atts, 'block-target')
				));
				break;

			case GD_TEMPLATE_ANCHORCONTROL_EMBED:
			case GD_TEMPLATE_ANCHORCONTROL_COPYSEARCHURL:
			case GD_TEMPLATE_ANCHORCONTROL_SHAREBYEMAIL:

				$this->merge_att($template_id, $atts, 'params', array(
					'data-toggle' => 'modal',
					'data-blocktarget' => $this->get_att($template_id, $atts, 'block-target')
				));
				$this->merge_att($template_id, $atts, 'blockfeedback-params', array(
					'data-target-title' => 'title',
				));
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_CURRENTURL:
				
				$this->append_att($template_id, $atts, 'class', 'btn btn-compact btn-link');
				break;

			case GD_TEMPLATE_ANCHORCONTROL_INVITENEWUSERS:

				$this->append_att($template_id, $atts, 'class', 'btn btn-default btn-block btn-invitenewusers');
				break;

			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBOTTOM:

				$this->append_att($template_id, $atts, 'class', 'close');
				break;

			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBTN:
				
				$this->append_att($template_id, $atts, 'class', 'btn btn-link');
				break;

			// case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO:

			// 	// Save the state of the sideinfo navigation (open or close) with a cookie
			// 	$this->merge_att($template_id, $atts, 'params', array(
			// 		'data-cookieid' => 'togglesideinfo',
			// 		'data-cookietarget' => '#'.GD_TEMPLATEID_PAGESECTIONGROUP_ID,
			// 		'data-cookieclass' => 'active-sideinfo',
			// 		'data-togglecookiebtn' => 'self',
			// 	));
			// 	break;
		}

		return parent::init_atts($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_ANCHORCONTROL_PRINT:
				$this->add_jsmethod($ret, 'controlPrint');
				break;

			case GD_TEMPLATE_ANCHORCONTROL_SHARE_FACEBOOK:
			case GD_TEMPLATE_ANCHORCONTROL_SHARE_TWITTER:
			case GD_TEMPLATE_ANCHORCONTROL_SHARE_LINKEDIN:
				$this->add_jsmethod($ret, 'controlSocialMedia');
				break;

			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGE:
			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBTN:
			case GD_TEMPLATE_ANCHORCONTROL_CLOSEPAGEBOTTOM:
				$this->add_jsmethod($ret, 'closePageTab');
				break;

			case GD_TEMPLATE_ANCHORCONTROL_TOGGLEQUICKVIEWINFO:
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO:
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS:
			// case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO_BACK:
			case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFOXS_BACK:
				$this->add_jsmethod($ret, 'offcanvasToggle');
				break;
		}
		// switch ($template_id) {

		// 	case GD_TEMPLATE_ANCHORCONTROL_TOGGLESIDEINFO:
		// 		$this->add_jsmethod($ret, 'cookieToggleClass');
		// 		break;
		// }
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_AnchorControls();