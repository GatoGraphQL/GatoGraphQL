<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('POP_RESOURCELOADER_ANALYTICS', PoP_TemplateIDUtils::get_template_definition('analytics'));
define ('POP_RESOURCELOADER_DATERANGE', PoP_TemplateIDUtils::get_template_definition('daterange'));
define ('POP_RESOURCELOADER_DYNAMICMAXHEIGHT', PoP_TemplateIDUtils::get_template_definition('dynamicmaxheight'));
define ('POP_RESOURCELOADER_MULTISELECT', PoP_TemplateIDUtils::get_template_definition('multiselect'));
define ('POP_RESOURCELOADER_PERFECTSCROLLBAR', PoP_TemplateIDUtils::get_template_definition('perfectscrollbar'));
define ('POP_RESOURCELOADER_TYPEAHEAD', PoP_TemplateIDUtils::get_template_definition('typeahead'));
define ('POP_RESOURCELOADER_WAYPOINTS', PoP_TemplateIDUtils::get_template_definition('waypoints'));
define ('POP_RESOURCELOADER_ADDEDITPOST', PoP_TemplateIDUtils::get_template_definition('addeditpost'));
// define ('POP_RESOURCELOADER_BLOCKFUNCTIONS', PoP_TemplateIDUtils::get_template_definition('block-functions'));
define ('POP_RESOURCELOADER_BOOTSTRAPCAROUSEL', PoP_TemplateIDUtils::get_template_definition('bootstrap-carousel'));
define ('POP_RESOURCELOADER_CONTROLS', PoP_TemplateIDUtils::get_template_definition('controls'));
define ('POP_RESOURCELOADER_EDITOR', PoP_TemplateIDUtils::get_template_definition('editor'));
define ('POP_RESOURCELOADER_FEATUREDIMAGE', PoP_TemplateIDUtils::get_template_definition('featuredimage'));
define ('POP_RESOURCELOADER_COOKIES', PoP_TemplateIDUtils::get_template_definition('cookies'));
define ('POP_RESOURCELOADER_FUNCTIONS', PoP_TemplateIDUtils::get_template_definition('functions'));
define ('POP_RESOURCELOADER_INPUTFUNCTIONS', PoP_TemplateIDUtils::get_template_definition('input-functions'));
define ('POP_RESOURCELOADER_EMBEDFUNCTIONS', PoP_TemplateIDUtils::get_template_definition('embed-functions'));
define ('POP_RESOURCELOADER_PRINTFUNCTIONS', PoP_TemplateIDUtils::get_template_definition('print-functions'));
define ('POP_RESOURCELOADER_CONTENTFUNCTIONS', PoP_TemplateIDUtils::get_template_definition('content-functions'));
define ('POP_RESOURCELOADER_TARGETFUNCTIONS', PoP_TemplateIDUtils::get_template_definition('target-functions'));
define ('POP_RESOURCELOADER_SOCIALMEDIA', PoP_TemplateIDUtils::get_template_definition('socialmedia'));
define ('POP_RESOURCELOADER_EMBEDDABLE', PoP_TemplateIDUtils::get_template_definition('embeddable'));
define ('POP_RESOURCELOADER_BLOCKDATAQUERY', PoP_TemplateIDUtils::get_template_definition('block-dataquery'));
define ('POP_RESOURCELOADER_BLOCKGROUPDATAQUERY', PoP_TemplateIDUtils::get_template_definition('blockgroup-dataquery'));
define ('POP_RESOURCELOADER_MENUS', PoP_TemplateIDUtils::get_template_definition('menus'));
define ('POP_RESOURCELOADER_DATASETCOUNT', PoP_TemplateIDUtils::get_template_definition('dataset-count'));
define ('POP_RESOURCELOADER_REPLICATE', PoP_TemplateIDUtils::get_template_definition('replicate'));
define ('POP_RESOURCELOADER_FORMS', PoP_TemplateIDUtils::get_template_definition('forms'));
define ('POP_RESOURCELOADER_LINKS', PoP_TemplateIDUtils::get_template_definition('links'));
define ('POP_RESOURCELOADER_CLASSES', PoP_TemplateIDUtils::get_template_definition('classes'));
define ('POP_RESOURCELOADER_SCROLLS', PoP_TemplateIDUtils::get_template_definition('scrolls'));
define ('POP_RESOURCELOADER_ONLINEOFFLINE', PoP_TemplateIDUtils::get_template_definition('onlineoffline'));
define ('POP_RESOURCELOADER_EVENTREACTIONS', PoP_TemplateIDUtils::get_template_definition('event-reactions'));
define ('POP_RESOURCELOADER_FEEDBACKMESSAGE', PoP_TemplateIDUtils::get_template_definition('feedback-message'));
define ('POP_RESOURCELOADER_COREHANDLEBARSHELPERS', PoP_TemplateIDUtils::get_template_definition('core-helpers-handlebars'));
define ('POP_RESOURCELOADER_MEDIAMANAGERCORS', PoP_TemplateIDUtils::get_template_definition('mediamanager-cors'));
define ('POP_RESOURCELOADER_MEDIAMANAGER', PoP_TemplateIDUtils::get_template_definition('mediamanager'));
define ('POP_RESOURCELOADER_MENTIONS', PoP_TemplateIDUtils::get_template_definition('mentions'));
define ('POP_RESOURCELOADER_MODALS', PoP_TemplateIDUtils::get_template_definition('modals'));
define ('POP_RESOURCELOADER_SYSTEM', PoP_TemplateIDUtils::get_template_definition('system'));
define ('POP_RESOURCELOADER_TABS', PoP_TemplateIDUtils::get_template_definition('tabs'));
define ('POP_RESOURCELOADER_USERACCOUNT', PoP_TemplateIDUtils::get_template_definition('user-account'));
define ('POP_RESOURCELOADER_WINDOW', PoP_TemplateIDUtils::get_template_definition('window'));

class PoP_CoreProcessors_ResourceLoaderProcessor extends PoP_ResourceLoaderProcessor {

	function get_resources_to_process() {

		return array(
			// POP_RESOURCELOADER_ANALYTICS,
			POP_RESOURCELOADER_DATERANGE,
			POP_RESOURCELOADER_DYNAMICMAXHEIGHT,
			POP_RESOURCELOADER_MULTISELECT,
			POP_RESOURCELOADER_PERFECTSCROLLBAR,
			POP_RESOURCELOADER_TYPEAHEAD,
			POP_RESOURCELOADER_WAYPOINTS,
			POP_RESOURCELOADER_ADDEDITPOST,
			// POP_RESOURCELOADER_BLOCKFUNCTIONS,
			POP_RESOURCELOADER_BOOTSTRAPCAROUSEL,
			POP_RESOURCELOADER_CONTROLS,
			POP_RESOURCELOADER_EDITOR,
			POP_RESOURCELOADER_FEATUREDIMAGE,
			POP_RESOURCELOADER_COOKIES,
			POP_RESOURCELOADER_FUNCTIONS,
			POP_RESOURCELOADER_INPUTFUNCTIONS,
			POP_RESOURCELOADER_EMBEDFUNCTIONS,
			POP_RESOURCELOADER_PRINTFUNCTIONS,
			POP_RESOURCELOADER_CONTENTFUNCTIONS,
			POP_RESOURCELOADER_TARGETFUNCTIONS,
			POP_RESOURCELOADER_SOCIALMEDIA,
			POP_RESOURCELOADER_EMBEDDABLE,
			POP_RESOURCELOADER_BLOCKDATAQUERY,
			POP_RESOURCELOADER_BLOCKGROUPDATAQUERY,
			POP_RESOURCELOADER_MENUS,
			POP_RESOURCELOADER_DATASETCOUNT,
			POP_RESOURCELOADER_REPLICATE,
			POP_RESOURCELOADER_FORMS,
			POP_RESOURCELOADER_LINKS,
			POP_RESOURCELOADER_CLASSES,
			POP_RESOURCELOADER_SCROLLS,
			POP_RESOURCELOADER_ONLINEOFFLINE,
			POP_RESOURCELOADER_EVENTREACTIONS,
			POP_RESOURCELOADER_FEEDBACKMESSAGE,
			POP_RESOURCELOADER_COREHANDLEBARSHELPERS,
			POP_RESOURCELOADER_MEDIAMANAGERCORS,
			POP_RESOURCELOADER_MEDIAMANAGER,
			POP_RESOURCELOADER_MENTIONS,
			POP_RESOURCELOADER_MODALS,
			POP_RESOURCELOADER_SYSTEM,
			POP_RESOURCELOADER_TABS,
			POP_RESOURCELOADER_USERACCOUNT,
			POP_RESOURCELOADER_WINDOW,
		);
	}
	
	function get_filename($resource) {
	
		$filenames = array(
			// POP_RESOURCELOADER_ANALYTICS => 'analytics',
			POP_RESOURCELOADER_DATERANGE => 'daterange',
			POP_RESOURCELOADER_DYNAMICMAXHEIGHT => 'dynamicmaxheight',
			POP_RESOURCELOADER_MULTISELECT => 'multiselect',
			POP_RESOURCELOADER_PERFECTSCROLLBAR => 'perfectscrollbar',
			POP_RESOURCELOADER_TYPEAHEAD => 'typeahead',
			POP_RESOURCELOADER_WAYPOINTS => 'waypoints',
			POP_RESOURCELOADER_ADDEDITPOST => 'addeditpost',
			// POP_RESOURCELOADER_BLOCKFUNCTIONS => 'block-functions',
			POP_RESOURCELOADER_BOOTSTRAPCAROUSEL => 'bootstrap-carousel',
			POP_RESOURCELOADER_CONTROLS => 'controls',
			POP_RESOURCELOADER_EDITOR => 'editor',
			POP_RESOURCELOADER_FEATUREDIMAGE => 'featuredimage',
			POP_RESOURCELOADER_COOKIES => 'cookies',
			POP_RESOURCELOADER_FUNCTIONS => 'functions',
			POP_RESOURCELOADER_INPUTFUNCTIONS => 'input-functions',
			POP_RESOURCELOADER_EMBEDFUNCTIONS => 'embed-functions',
			POP_RESOURCELOADER_PRINTFUNCTIONS => 'print-functions',
			POP_RESOURCELOADER_CONTENTFUNCTIONS => 'content-functions',
			POP_RESOURCELOADER_TARGETFUNCTIONS => 'target-functions',
			POP_RESOURCELOADER_SOCIALMEDIA => 'socialmedia',
			POP_RESOURCELOADER_EMBEDDABLE => 'embeddable',
			POP_RESOURCELOADER_BLOCKDATAQUERY => 'block-dataquery',
			POP_RESOURCELOADER_BLOCKGROUPDATAQUERY => 'blockgroup-dataquery',
			POP_RESOURCELOADER_MENUS => 'menus',
			POP_RESOURCELOADER_DATASETCOUNT => 'dataset-count',
			POP_RESOURCELOADER_REPLICATE => 'replicate',
			POP_RESOURCELOADER_FORMS => 'forms',
			POP_RESOURCELOADER_LINKS => 'links',
			POP_RESOURCELOADER_CLASSES => 'classes',
			POP_RESOURCELOADER_SCROLLS => 'scrolls',
			POP_RESOURCELOADER_ONLINEOFFLINE => 'onlineoffline',
			POP_RESOURCELOADER_EVENTREACTIONS => 'event-reactions',
			POP_RESOURCELOADER_FEEDBACKMESSAGE => 'feedback-message',
			POP_RESOURCELOADER_COREHANDLEBARSHELPERS => 'helpers.handlebars',
			POP_RESOURCELOADER_MEDIAMANAGERCORS => 'mediamanager-cors',
			POP_RESOURCELOADER_MEDIAMANAGER => 'mediamanager',
			POP_RESOURCELOADER_MENTIONS => 'mentions',
			POP_RESOURCELOADER_MODALS => 'modals',
			POP_RESOURCELOADER_SYSTEM => 'system',
			POP_RESOURCELOADER_TABS => 'tabs',
			POP_RESOURCELOADER_USERACCOUNT => 'user-account',
			POP_RESOURCELOADER_WINDOW => 'window',
		);
		if ($filename = $filenames[$resource]) {
			return $filename;
		}

		return parent::get_filename($resource);
	}
		
	function extract_mapping($resource) {

		// No need to extract the mapping from this file (also, it doesn't exist under that get_dir() folder)
		switch ($resource) {

			case POP_RESOURCELOADER_COREHANDLEBARSHELPERS:
				
				return false;
		}
	
		return parent::extract_mapping($resource);
	}
	
	function get_version($resource) {
	
		return POP_COREPROCESSORS_VERSION;
	}
	
	function get_dir($resource) {

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		switch($resource) {

			// case POP_RESOURCELOADER_ANALYTICS:
			case POP_RESOURCELOADER_DATERANGE:
			case POP_RESOURCELOADER_DYNAMICMAXHEIGHT:
			case POP_RESOURCELOADER_MULTISELECT:
			case POP_RESOURCELOADER_PERFECTSCROLLBAR:
			case POP_RESOURCELOADER_TYPEAHEAD:
			case POP_RESOURCELOADER_WAYPOINTS:
			
				return POP_COREPROCESSORS_DIR.'/js/'.$subpath.'libraries/3rdparties';
		}
	
		return POP_COREPROCESSORS_DIR.'/js/'.$subpath.'libraries';
	}
	
	function get_asset_path($resource) {
	
		switch ($resource) {

			// case POP_RESOURCELOADER_ANALYTICS:
			case POP_RESOURCELOADER_DATERANGE:
			case POP_RESOURCELOADER_DYNAMICMAXHEIGHT:
			case POP_RESOURCELOADER_MULTISELECT:
			case POP_RESOURCELOADER_PERFECTSCROLLBAR:
			case POP_RESOURCELOADER_TYPEAHEAD:
			case POP_RESOURCELOADER_WAYPOINTS:

				return POP_COREPROCESSORS_DIR.'/js/libraries/3rdparties/'.$this->get_filename($resource).'.js';
		}

		return POP_COREPROCESSORS_DIR.'/js/libraries/'.$this->get_filename($resource).'.js';
	}
	
	function get_path($resource) {

		$afterpath = '';
		switch($resource) {

			// case POP_RESOURCELOADER_ANALYTICS:
			case POP_RESOURCELOADER_DATERANGE:
			case POP_RESOURCELOADER_DYNAMICMAXHEIGHT:
			case POP_RESOURCELOADER_MULTISELECT:
			case POP_RESOURCELOADER_PERFECTSCROLLBAR:
			case POP_RESOURCELOADER_TYPEAHEAD:
			case POP_RESOURCELOADER_WAYPOINTS:
			
				$afterpath = '/3rdparties';
				break;
		}

		$subpath = PoP_Frontend_ServerUtils::use_minified_resources() ? 'dist/' : '';
		return POP_COREPROCESSORS_URI.'/js/'.$subpath.'libraries'.$afterpath;
	}

	function get_jsobjects($resource) {

		$objects = array(
			// POP_RESOURCELOADER_ANALYTICS => array(
			// 	'popGA',
			// ),
			POP_RESOURCELOADER_DATERANGE => array(
				'popDateRange',
			),
			POP_RESOURCELOADER_DYNAMICMAXHEIGHT => array(
				'popDynamicMaxHeight',
			),
			POP_RESOURCELOADER_MULTISELECT => array(
				'popMultiselect',
			),
			POP_RESOURCELOADER_PERFECTSCROLLBAR => array(
				'popPerfectScrollbar',
			),
			POP_RESOURCELOADER_TYPEAHEAD => array(
				'popTypeahead',
			),
			POP_RESOURCELOADER_WAYPOINTS => array(
				'popWaypoints',
			),
			POP_RESOURCELOADER_ADDEDITPOST => array(
				'popAddEditPost',
			),
			// POP_RESOURCELOADER_BLOCKFUNCTIONS => array(
			// 	'popBlockFunctions',
			// ),
			POP_RESOURCELOADER_BOOTSTRAPCAROUSEL => array(
				'popBootstrapCarousel',
				'popBootstrapCarouselControls',
			),
			POP_RESOURCELOADER_CONTROLS => array(
				'popControls',
			),
			POP_RESOURCELOADER_EDITOR => array(
				'popEditor',
			),
			POP_RESOURCELOADER_FEATUREDIMAGE => array(
				'popFeaturedImage',
			),
			POP_RESOURCELOADER_COOKIES => array(
				'popCookies',
			),
			POP_RESOURCELOADER_FUNCTIONS => array(
				'popFunctions',
			),
			POP_RESOURCELOADER_INPUTFUNCTIONS => array(
				'popInputFunctions',
			),
			POP_RESOURCELOADER_EMBEDFUNCTIONS => array(
				'popEmbedFunctions',
			),
			POP_RESOURCELOADER_PRINTFUNCTIONS => array(
				'popPrintFunctions',
			),
			POP_RESOURCELOADER_CONTENTFUNCTIONS => array(
				'popContentFunctions',
			),
			POP_RESOURCELOADER_TARGETFUNCTIONS => array(
				'popTargetFunctions',
			),
			POP_RESOURCELOADER_SOCIALMEDIA => array(
				'popSocialMedia',
			),
			POP_RESOURCELOADER_EMBEDDABLE => array(
				'popEmbeddable',
			),
			POP_RESOURCELOADER_BLOCKDATAQUERY => array(
				'popBlockDataQuery',
			),
			POP_RESOURCELOADER_BLOCKGROUPDATAQUERY => array(
				'popBlockGroupDataQuery',
			),
			POP_RESOURCELOADER_MENUS => array(
				'popMenus',
			),
			POP_RESOURCELOADER_DATASETCOUNT => array(
				'popDatasetCount',
			),
			POP_RESOURCELOADER_REPLICATE => array(
				'popReplicate',
			),
			POP_RESOURCELOADER_FORMS => array(
				'popForms',
			),
			POP_RESOURCELOADER_LINKS => array(
				'popLinks',
			),
			POP_RESOURCELOADER_CLASSES => array(
				'popClasses',
			),
			POP_RESOURCELOADER_SCROLLS => array(
				'popScrolls',
			),
			POP_RESOURCELOADER_ONLINEOFFLINE => array(
				'popOnlineOffline',
			),
			POP_RESOURCELOADER_EVENTREACTIONS => array(
				'popEventReactions',
			),
			POP_RESOURCELOADER_FEEDBACKMESSAGE => array(
				'popFeedbackMessage',
			),
			POP_RESOURCELOADER_MEDIAMANAGERCORS => array(
				'popMediaManagerCORS',
			),
			POP_RESOURCELOADER_MEDIAMANAGER => array(
				'popMediaManager',
			),
			POP_RESOURCELOADER_MENTIONS => array(
				'popMentions',
			),
			POP_RESOURCELOADER_MODALS => array(
				'popModals',
			),
			POP_RESOURCELOADER_SYSTEM => array(
				'popSystem',
			),
			POP_RESOURCELOADER_TABS => array(
				'popTabs',
			),
			POP_RESOURCELOADER_USERACCOUNT => array(
				'popUserAccount',
			),
			POP_RESOURCELOADER_WINDOW => array(
				'popWindow',
			),
		);
		if ($object = $objects[$resource]) {

			return $object;
		}

		return parent::get_jsobjects($resource);
	}
	
	function get_dependencies($resource) {

		$dependencies = parent::get_dependencies($resource);
	
		switch ($resource) {

			case POP_RESOURCELOADER_DATERANGE:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_DATERANGEPICKER;
				break;
				
			case POP_RESOURCELOADER_DYNAMICMAXHEIGHT:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_DYNAMICMAXHEIGHT;
				break;
				
			case POP_RESOURCELOADER_MULTISELECT:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_BOOTSTRAPMULTISELECT;
				break;
				
			case POP_RESOURCELOADER_PERFECTSCROLLBAR:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_PERFECTSCROLLBAR;
				break;
				
			case POP_RESOURCELOADER_TYPEAHEAD:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_TYPEAHEAD;

				// Also add the Handlebar templates needed to render the typeahead views on runtime
				if ($typeahead_layouts = array_unique(apply_filters(
					'PoP_CoreProcessors_ResourceLoaderProcessor:typeahead:templates',
					array()
				))) {
					$dependencies = array_merge(
						$dependencies,
						$typeahead_layouts
					);
				}
				break;
				
			case POP_RESOURCELOADER_WAYPOINTS:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_WAYPOINTS;
				break;
				
			case POP_RESOURCELOADER_COOKIES:
			case POP_RESOURCELOADER_TABS:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_JQUERYCOOKIE;
				break;

			case POP_RESOURCELOADER_EMBEDDABLE:

				$dependencies[] = POP_RESOURCELOADER_EXTERNAL_FULLSCREEN;
				break;

			case POP_RESOURCELOADER_COREHANDLEBARSHELPERS:

				$dependencies[] = POP_RESOURCELOADER_FEEDBACKMESSAGE;
				break;
		}

		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_CoreProcessors_ResourceLoaderProcessor();
