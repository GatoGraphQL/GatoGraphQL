<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Header hook implementation functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_ResourceLoaderProcessor_Hooks {

	function __construct() {

		add_filter(
			'PoP_FrontEnd_ResourceLoaderProcessor:dependencies:manager',
			array($this, 'get_manager_dependencies')
		);

		add_filter(
			'PoP_CoreProcessors_ResourceLoaderProcessor:dependencies:multiselect',
			array($this, 'get_multiselect_dependencies')
		);

		add_filter(
			'GD_Template_ProcessorBase:template-resources',
			array($this, 'get_template_css_resources'),
			10,
			5
		);
	}

	function get_template_css_resources($resources, $template_id, $template_source, $atts, $processor) {

		switch ($template_id) {
			
			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG:
			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION:
			
				$resources[] = POP_RESOURCELOADER_CSS_BLOCKGROUPHOMEWELCOME;
				break;

			case GD_TEMPLATE_LAYOUT_TAG_DETAILS:

				$resources[] = POP_RESOURCELOADER_CSS_QUICKLINKGROUPS;
				break;

			case GD_TEMPLATE_BLOCKGROUP_AUTHOR:

				$resources[] = POP_RESOURCELOADER_CSS_BLOCKGROUPAUTHOR;
				break;

			case GD_TEMPLATE_FORM_MYPREFERENCES:

				$resources[] = POP_RESOURCELOADER_CSS_FORMMYPREFERENCES;
				break;

			case GD_TEMPLATE_BLOCK_COMMENTS_SCROLL:

				$resources[] = POP_RESOURCELOADER_CSS_BLOCKCOMMENTS;
				break;

			case GD_TEMPLATE_BLOCK_MESSAGES_HOME:

				$resources[] = POP_RESOURCELOADER_CSS_HOMEMESSAGE;
				break;

			case GD_TEMPLATE_SCROLL_OURSPONSORS_SMALLDETAILS:

				$resources[] = POP_RESOURCELOADER_CSS_SMALLDETAILS;
				break;
		}

		switch ($template_source) {
			
			case GD_TEMPLATESOURCE_LAYOUT_PREVIEWNOTIFICATION:
			case GD_TEMPLATESOURCE_LAYOUT_PREVIEWPOST:
			case GD_TEMPLATESOURCE_LAYOUT_PREVIEWUSER:
			
				$resources[] = POP_RESOURCELOADER_CSS_QUICKLINKGROUPS;
				break;

			case GD_TEMPLATESOURCE_FORMCOMPONENT_DATERANGE:

				$resources[] = POP_RESOURCELOADER_CSS_DATERANGEPICKER;
				break;

			case GD_TEMPLATESOURCE_FETCHMORE:

				$resources[] = POP_RESOURCELOADER_CSS_FETCHMORE;
				break;

			case GD_TEMPLATESOURCE_BLOCK:

				$resources[] = POP_RESOURCELOADER_CSS_BLOCK;
				break;

			case GD_TEMPLATESOURCE_SOCIALMEDIA_ITEM:
			case GD_TEMPLATESOURCE_SOCIALMEDIA:

				$resources[] = POP_RESOURCELOADER_CSS_SOCIALMEDIA;
				break;

			case GD_TEMPLATESOURCE_LAYOUT_USERPOSTINTERACTION:

				$resources[] = POP_RESOURCELOADER_CSS_FRAMEADDCOMMENTS;
				break;

			case GD_TEMPLATESOURCE_SPEECHBUBBLE:

				$resources[] = POP_RESOURCELOADER_CSS_SPEECHBUBBLE;
				break;

			// case GD_TEMPLATESOURCE_FORMCOMPONENT_FEATUREDIMAGE:
			case GD_TEMPLATESOURCE_FORMCOMPONENT_FEATUREDIMAGE_INNER:

				$resources[] = POP_RESOURCELOADER_CSS_FEATUREDIMAGE;
				break;

			case GD_TEMPLATESOURCE_WIDGET:

				$resources[] = POP_RESOURCELOADER_CSS_WIDGET;
				break;

			case GD_TEMPLATESOURCE_LAYOUT_MAXHEIGHT:

				$resources[] = POP_RESOURCELOADER_CSS_DYNAMICMAXHEIGHT;
				break;
		}

		if ($processor->get_att($template_id, $atts, 'use-skeletonscreen')) {

			$resources[] = POP_RESOURCELOADER_CSS_SKELETONSCREEN;
		}

		// Artificial property added to identify the template when adding template-resources
		if ($resourceloader_att = $processor->get_att($template_id, $atts, 'resourceloader')) {

			if ($resourceloader_att == 'block-carousel') {

				$resources[] = POP_RESOURCELOADER_CSS_BLOCKCAROUSEL;
			}
			elseif ($resourceloader_att == 'blockgroup-authorsections') {

				$resources[] = POP_RESOURCELOADER_CSS_BLOCKGROUPAUTHORSECTIONS;
			}
			elseif ($resourceloader_att == 'functionalblock') {

				$resources[] = POP_RESOURCELOADER_CSS_FUNCTIONALBLOCK;
			}
			elseif ($resourceloader_att == 'functionbutton') {

				$resources[] = POP_RESOURCELOADER_CSS_FUNCTIONBUTTON;
			}
			elseif ($resourceloader_att == 'socialmedia') {

				$resources[] = POP_RESOURCELOADER_CSS_SOCIALMEDIA;
			}
			elseif ($resourceloader_att == 'side-sections-menu') {

				$resources[] = POP_RESOURCELOADER_CSS_SIDESECTIONSMENU;
			}
			elseif ($resourceloader_att == 'littleguy') {

				$resources[] = POP_RESOURCELOADER_CSS_LITTLEGUY;
			}
			elseif ($resourceloader_att == 'block-notifications') {

				$resources[] = POP_RESOURCELOADER_CSS_BLOCKNOTIFICATIONS;
			}
			elseif ($resourceloader_att == 'scroll-notifications') {

				$resources[] = POP_RESOURCELOADER_CSS_SCROLLNOTIFICATIONS;
			}
			elseif ($resourceloader_att == 'structure') {

				$resources[] = POP_RESOURCELOADER_CSS_STRUCTURE;
			}
			elseif ($resourceloader_att == 'layout') {

				$resources[] = POP_RESOURCELOADER_CSS_LAYOUT;
			}
			elseif ($resourceloader_att == 'thumb-feed') {

				$resources[] = POP_RESOURCELOADER_CSS_FEEDTHUMB;
			}
		}

		// Allow to inject the $template_id here for several cases
		if ($collapse_hometop_template_id = apply_filters(
			'PoPTheme_Wassup_ResourceLoaderProcessor_Hooks:css-resources:collapse-hometop',
			''
		)) {

			$resources[] = POP_RESOURCELOADER_CSS_COLLAPSEHOMETOP;
		}

		return $resources;
	}

	function get_multiselect_dependencies($dependencies) {

		$dependencies[] = POP_RESOURCELOADER_CSS_MULTISELECT;
		return $dependencies;
	}

	function get_manager_dependencies($dependencies) {

		// Generic css
		$dependencies[] = POP_RESOURCELOADER_CSS_BACKGROUNDIMAGE;
		$dependencies[] = POP_RESOURCELOADER_CSS_PAGESECTIONGROUP;
		$dependencies[] = POP_RESOURCELOADER_CSS_THEMEWASSUP;
		$dependencies[] = POP_RESOURCELOADER_EXTERNAL_CSS_FONTAWESOME;

		// All functions in files condition-functions.js and ure-aal-functions.js 
		// are invoked through `popJSLibraryManager.execute(options.hash.method, ...`
		// Because their name is assigned in variable `options.hash.method`, and not directly, then these are not picke up
		// by the resourceLoader mapping process. As such, simply always add this JS file to be loaded
		$dependencies[] = POP_RESOURCELOADER_CONDITIONFUNCTIONS;
		$dependencies[] = POP_RESOURCELOADER_UREAALFUNCTIONS;
		return $dependencies;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_ResourceLoaderProcessor_Hooks();
