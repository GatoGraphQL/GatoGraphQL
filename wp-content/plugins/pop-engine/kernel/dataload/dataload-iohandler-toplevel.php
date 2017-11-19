<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_DataLoad_TopLevelIOHandler extends GD_DataLoad_CheckpointIOHandler {

    function get_vars($atts, $iohandler_atts) {

		$ret = parent::get_vars($atts, $iohandler_atts);

		$vars = GD_TemplateManager_Utils::get_vars();
		$ret[GD_URLPARAM_THEME] = $vars['theme'];
		$ret[GD_URLPARAM_THEMEMODE] = $vars['thememode'];
		$ret[GD_URLPARAM_THEMESTYLE] = $vars['themestyle'];
		$ret[GD_URLPARAM_FORMAT] = $vars['format'];

		// Silent document? (Opposite to Update the browser URL and Title?)
		global $gd_template_settingsmanager;
		$ret[GD_URLPARAM_SILENTDOCUMENT] = $gd_template_settingsmanager->silent_document();
		$ret[GD_URLPARAM_STORELOCAL] = $gd_template_settingsmanager->store_local();
		
		return $ret;
	}

	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);

		$vars = $this->get_vars($vars_atts, $iohandler_atts);
		$main_vars = GD_TemplateManager_Utils::get_vars();

		// Send the current selected theme back
		$ret[GD_DATALOAD_PARAMS] = array();

		// Push URL Atts: attributes that must be added to the URL in the browser. Sent this way so that we can add these
		// also when switching tabPanes (format=fullview, etc)			
		$ret[GD_DATALOAD_PUSHURLATTS] = array();

		if (GD_TemplateManager_Utils::loading_frame()) {

			// Comment Leo 05/04/2017: Create the params array only in the loading_frame()
			// Before it was outside, and calling the initial-frames page brought params=[], 
			// and this was overriding the params in the topLevelFeedback removing all info there

			// Add the version to the topLevel feedback to be sent in the URL params
			$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_VERSION] = pop_version();

			// Send the current selected theme back
			$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_THEME] = $vars[GD_URLPARAM_THEME];
			$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_THEMEMODE] = $vars[GD_URLPARAM_THEMEMODE];
			$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_THEMESTYLE] = $vars[GD_URLPARAM_THEMESTYLE];

			if ($format = $vars[GD_URLPARAM_FORMAT]) {
				$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_SETTINGSFORMAT] = $format;
			}
			// Comment Leo 09/11/2017: removed param "mangled" because it can't be used anymore on "loading-frame", since the website depends on configuration generated through /generate-theme/, which depends on the value of the template-definition
			// if ($mangled = $vars[GD_URLPARAM_MANGLED]) {
			// 	$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_MANGLED] = $mangled;
			// }
			if (PoP_ServerUtils::enable_config_by_params()) {

				if ($config = $vars[POP_URLPARAM_CONFIG]) {
					$ret[GD_DATALOAD_PARAMS][POP_URLPARAM_CONFIG] = $config;
				}
			}

			// Theme: send only when it's not the default one (so the user can still see/copy/share the embed/print URL)
			if (!$main_vars['theme-isdefault']) {
				$ret[GD_DATALOAD_PUSHURLATTS][GD_URLPARAM_THEME] = $main_vars['theme'];
			}
			if (!$main_vars['thememode-isdefault']) {
				$ret[GD_DATALOAD_PUSHURLATTS][GD_URLPARAM_THEMEMODE] = $main_vars['thememode'];
			}		
			if (!$main_vars['themestyle-isdefault']) {
				$ret[GD_DATALOAD_PUSHURLATTS][GD_URLPARAM_THEMESTYLE] = $main_vars['themestyle'];
			}		
		}

		// Send back the URL to push in the browser History
		$ret[GD_URLPARAM_URL] = GD_TemplateManager_Utils::get_current_url();

		// Any errors? Send them back
		$ret[GD_URLPARAM_ERROR] = null;
		if (GD_TemplateManager_Utils::$errors) {
			if (count(GD_TemplateManager_Utils::$errors) > 1) {
				$ret[GD_URLPARAM_ERROR] = 
					__('Ops, there were some errors:', 'pop-engine').
					implode('<br/>', GD_TemplateManager_Utils::$errors);
			}
			else {
				$ret[GD_URLPARAM_ERROR] = __('Ops, there was an error: ', 'pop-engine').GD_TemplateManager_Utils::$errors[0];
			}
		}

		// Silent document? (Opposite to Update the browser URL and Title?)
		$ret[GD_URLPARAM_SILENTDOCUMENT] = $vars[GD_URLPARAM_SILENTDOCUMENT];		
		$ret[GD_URLPARAM_STORELOCAL] = $vars[GD_URLPARAM_STORELOCAL];
		
		// All page properties
		$post = $main_vars['global-state']['post']/*global $post*/;
		if ($main_vars['global-state']['is-page']/*is_page()*/) {
			$parentpageid = $post->ID;
		}
		// retrieve the page for the category for the post
		elseif ($main_vars['global-state']['is-single']/*is_single()*/) {
			$parentpageid = gd_post_parentpageid($post->ID);
		}
		// retrieve the page for the authors
		elseif ($main_vars['global-state']['is-author']/*is_author()*/) {
			$author = $main_vars['global-state']['author']/*global $author*/;
			$parentpageid = gd_author_parentpageid($author);
		}
		if ($parentpageid) {
			$ret[GD_URLPARAM_PARENTPAGEID] = $parentpageid;
		}

		$ret[GD_URLPARAM_TITLE] = gd_get_document_title();

		// // Nonces for validation for the Media Manager
		// $ret[GD_URLPARAM_NONCES] = array(
		// 	'media-form' => wp_create_nonce('media-form'),
		// 	'media-send-to-editor' => wp_create_nonce('media-send-to-editor'),
		// );

		// Allow plugins to keep adding stuff. Eg: language from qTranslate
		return apply_filters('GD_DataLoad_TopLevelIOHandler:feedback', $ret);
	}
}
	
