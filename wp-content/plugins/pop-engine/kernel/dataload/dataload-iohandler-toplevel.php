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
		$ret[GD_DATALOAD_PUSHURLATTS] = array();

		// Push URL Atts: attributes that must be added to the URL in the browser. Sent this way so that we can add these
		// also when switching tabPanes (format=fullview, etc)

		if (GD_TemplateManager_Utils::loading_frame()) {

			// Send the current selected theme back
			$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_THEME] = $vars[GD_URLPARAM_THEME];
			$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_THEMEMODE] = $vars[GD_URLPARAM_THEMEMODE];
			$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_THEMESTYLE] = $vars[GD_URLPARAM_THEMESTYLE];
			$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_SETTINGSFORMAT] = $vars[GD_URLPARAM_FORMAT];

			if ($datastructure = $vars[GD_URLPARAM_DATASTRUCTURE]) {
				$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_DATASTRUCTURE] = $datastructure;
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
		global $post;
		if (is_page()) {
			$parentpageid = $post->ID;
		}
		// retrieve the page for the category for the post
		elseif (is_single()) {
			$parentpageid = gd_post_parentpageid($post->ID);
		}
		// retrieve the page for the authors
		elseif (is_author()) {
			global $author;
			$parentpageid = gd_author_parentpageid($author);
		}
		if ($parentpageid) {
			$ret[GD_URLPARAM_PARENTPAGEID] = $parentpageid;
		}

		$ret[GD_URLPARAM_TITLE] = gd_get_document_title();

		// Allow plugins to keep adding stuff. In particular: language from qTranslate
		return apply_filters('GD_DataLoad_TopLevelIOHandler:feedback', $ret);
	}
}
	
