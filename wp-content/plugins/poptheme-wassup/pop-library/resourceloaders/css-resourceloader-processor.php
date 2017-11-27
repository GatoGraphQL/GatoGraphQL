<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_RESOURCELOADER_CSS_BLOCKGROUPHOMEWELCOME', PoP_TemplateIDUtils::get_template_definition('css-blockgroup-home-welcome'));
define ('POP_RESOURCELOADER_CSS_COLLAPSEHOMETOP', PoP_TemplateIDUtils::get_template_definition('css-collapse-hometop'));
define ('POP_RESOURCELOADER_CSS_QUICKLINKGROUPS', PoP_TemplateIDUtils::get_template_definition('css-quicklinkgroups'));
define ('POP_RESOURCELOADER_CSS_DATERANGEPICKER', PoP_TemplateIDUtils::get_template_definition('css-daterangepicker'));
define ('POP_RESOURCELOADER_CSS_SKELETONSCREEN', PoP_TemplateIDUtils::get_template_definition('css-skeletonscreen'));
define ('POP_RESOURCELOADER_CSS_BLOCKCAROUSEL', PoP_TemplateIDUtils::get_template_definition('css-blockcarousel'));
define ('POP_RESOURCELOADER_CSS_FETCHMORE', PoP_TemplateIDUtils::get_template_definition('css-fetchmore'));
define ('POP_RESOURCELOADER_CSS_BLOCKGROUPAUTHOR', PoP_TemplateIDUtils::get_template_definition('css-blockgroup-author'));
define ('POP_RESOURCELOADER_CSS_BLOCKGROUPAUTHORSECTIONS', PoP_TemplateIDUtils::get_template_definition('css-blockgroup-authorsections'));
define ('POP_RESOURCELOADER_CSS_BLOCK', PoP_TemplateIDUtils::get_template_definition('css-block'));
define ('POP_RESOURCELOADER_CSS_FUNCTIONALBLOCK', PoP_TemplateIDUtils::get_template_definition('css-functionalblock'));
define ('POP_RESOURCELOADER_CSS_FUNCTIONBUTTON', PoP_TemplateIDUtils::get_template_definition('css-functionbutton'));
define ('POP_RESOURCELOADER_CSS_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('css-socialmedia'));
define ('POP_RESOURCELOADER_CSS_FORMMYPREFERENCES', PoP_TemplateIDUtils::get_template_definition('css-form-mypreferences'));
define ('POP_RESOURCELOADER_CSS_BLOCKCOMMENTS', PoP_TemplateIDUtils::get_template_definition('css-block-comments'));
define ('POP_RESOURCELOADER_CSS_FRAMEADDCOMMENTS', PoP_TemplateIDUtils::get_template_definition('css-frame-addcomments'));
define ('POP_RESOURCELOADER_CSS_SIDESECTIONSMENU', PoP_TemplateIDUtils::get_template_definition('css-side-sections-menu'));
define ('POP_RESOURCELOADER_CSS_LITTLEGUY', PoP_TemplateIDUtils::get_template_definition('css-littleguy'));
define ('POP_RESOURCELOADER_CSS_SPEECHBUBBLE', PoP_TemplateIDUtils::get_template_definition('css-speechbubble'));
define ('POP_RESOURCELOADER_CSS_FEATUREDIMAGE', PoP_TemplateIDUtils::get_template_definition('css-featuredimage'));
define ('POP_RESOURCELOADER_CSS_MULTISELECT', PoP_TemplateIDUtils::get_template_definition('css-multiselect'));
define ('POP_RESOURCELOADER_CSS_HOMEMESSAGE', PoP_TemplateIDUtils::get_template_definition('css-homemessage'));
define ('POP_RESOURCELOADER_CSS_SMALLDETAILS', PoP_TemplateIDUtils::get_template_definition('css-smalldetails'));
define ('POP_RESOURCELOADER_CSS_BLOCKNOTIFICATIONS', PoP_TemplateIDUtils::get_template_definition('css-block-notifications'));
define ('POP_RESOURCELOADER_CSS_SCROLLNOTIFICATIONS', PoP_TemplateIDUtils::get_template_definition('css-scroll-notifications'));
define ('POP_RESOURCELOADER_CSS_WIDGET', PoP_TemplateIDUtils::get_template_definition('css-widget'));
define ('POP_RESOURCELOADER_CSS_DYNAMICMAXHEIGHT', PoP_TemplateIDUtils::get_template_definition('css-dynamicmaxheight'));

// class PoPTheme_Wassup_CSSResourceLoaderProcessor extends PoP_CSSComponentResourceLoaderProcessor {
class PoPTheme_Wassup_CSSResourceLoaderProcessor extends PoP_CSSResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			POP_RESOURCELOADER_CSS_BLOCKGROUPHOMEWELCOME,
			POP_RESOURCELOADER_CSS_COLLAPSEHOMETOP,
			POP_RESOURCELOADER_CSS_QUICKLINKGROUPS,
			POP_RESOURCELOADER_CSS_DATERANGEPICKER,
			POP_RESOURCELOADER_CSS_SKELETONSCREEN,
			POP_RESOURCELOADER_CSS_BLOCKCAROUSEL,
			POP_RESOURCELOADER_CSS_FETCHMORE,
			POP_RESOURCELOADER_CSS_BLOCKGROUPAUTHOR,
			POP_RESOURCELOADER_CSS_BLOCKGROUPAUTHORSECTIONS,
			POP_RESOURCELOADER_CSS_BLOCK,
			POP_RESOURCELOADER_CSS_FUNCTIONALBLOCK,
			POP_RESOURCELOADER_CSS_FUNCTIONBUTTON,
			POP_RESOURCELOADER_CSS_SOCIALMEDIA,
			POP_RESOURCELOADER_CSS_FORMMYPREFERENCES,
			POP_RESOURCELOADER_CSS_BLOCKCOMMENTS,
			POP_RESOURCELOADER_CSS_FRAMEADDCOMMENTS,
			POP_RESOURCELOADER_CSS_SIDESECTIONSMENU,
			POP_RESOURCELOADER_CSS_LITTLEGUY,
			POP_RESOURCELOADER_CSS_SPEECHBUBBLE,
			POP_RESOURCELOADER_CSS_FEATUREDIMAGE,
			POP_RESOURCELOADER_CSS_MULTISELECT,
			POP_RESOURCELOADER_CSS_HOMEMESSAGE,
			POP_RESOURCELOADER_CSS_SMALLDETAILS,
			POP_RESOURCELOADER_CSS_BLOCKNOTIFICATIONS,
			POP_RESOURCELOADER_CSS_SCROLLNOTIFICATIONS,
			POP_RESOURCELOADER_CSS_WIDGET,
			POP_RESOURCELOADER_CSS_DYNAMICMAXHEIGHT,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			POP_RESOURCELOADER_CSS_BLOCKGROUPHOMEWELCOME => 'blockgroup-home-welcome',
			POP_RESOURCELOADER_CSS_COLLAPSEHOMETOP => 'collapse-hometop',
			POP_RESOURCELOADER_CSS_QUICKLINKGROUPS => 'quicklinkgroups',
			POP_RESOURCELOADER_CSS_DATERANGEPICKER => 'daterangepicker',
			POP_RESOURCELOADER_CSS_SKELETONSCREEN => 'skeleton-screen',
			POP_RESOURCELOADER_CSS_BLOCKCAROUSEL => 'block-carousel',
			POP_RESOURCELOADER_CSS_FETCHMORE => 'fetchmore',
			POP_RESOURCELOADER_CSS_BLOCKGROUPAUTHOR => 'blockgroup-author',
			POP_RESOURCELOADER_CSS_BLOCKGROUPAUTHORSECTIONS => 'blockgroup-authorsections',
			POP_RESOURCELOADER_CSS_BLOCK => 'block',
			POP_RESOURCELOADER_CSS_FUNCTIONALBLOCK => 'functionalblock',
			POP_RESOURCELOADER_CSS_FUNCTIONBUTTON => 'functionbutton',
			POP_RESOURCELOADER_CSS_SOCIALMEDIA => 'socialmedia',
			POP_RESOURCELOADER_CSS_FORMMYPREFERENCES => 'form-mypreferences',
			POP_RESOURCELOADER_CSS_BLOCKCOMMENTS => 'block-comments',
			POP_RESOURCELOADER_CSS_FRAMEADDCOMMENTS => 'frame-addcomments',
			POP_RESOURCELOADER_CSS_SIDESECTIONSMENU => 'side-sections-menu',
			POP_RESOURCELOADER_CSS_LITTLEGUY => 'littleguy',
			POP_RESOURCELOADER_CSS_SPEECHBUBBLE => 'speechbubble',
			POP_RESOURCELOADER_CSS_FEATUREDIMAGE => 'featuredimage',
			POP_RESOURCELOADER_CSS_MULTISELECT => 'multiselect',
			POP_RESOURCELOADER_CSS_HOMEMESSAGE => 'homemessage',
			POP_RESOURCELOADER_CSS_SMALLDETAILS => 'smalldetails',
			POP_RESOURCELOADER_CSS_BLOCKNOTIFICATIONS => 'block-notifications',
			POP_RESOURCELOADER_CSS_SCROLLNOTIFICATIONS => 'scroll-notifications',
			POP_RESOURCELOADER_CSS_WIDGET => 'widget',
			POP_RESOURCELOADER_CSS_DYNAMICMAXHEIGHT => 'dynamicmaxheight',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
	
	function get_version($resource) {
	
		return POPTHEME_WASSUP_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POPTHEME_WASSUP_DIR.'/css/'.$subpath.'templates';
	}
	
	function get_asset_path($resource) {

		return POPTHEME_WASSUP_DIR.'/css/templates/'.$this->get_filename($resource).'.css';
	}
	
	function get_path($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POPTHEME_WASSUP_URI.'/css/'.$subpath.'templates';
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_CSSResourceLoaderProcessor();
