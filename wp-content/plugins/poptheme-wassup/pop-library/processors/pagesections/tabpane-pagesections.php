<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * All IDs
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATEID_PAGESECTIONID_HOVER', 'ps-hover');
define ('GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR', 'ps-navigator');
define ('GD_TEMPLATEID_PAGESECTIONID_ADDONS', 'ps-addons');
define ('GD_TEMPLATEID_PAGESECTIONID_MAIN', 'ps-main');
define ('GD_TEMPLATEID_PAGESECTIONID_SIDEINFO', 'ps-sideinfo');
define ('GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWMAIN', 'ps-quickview');
define ('GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWSIDEINFO', 'ps-quickviewinfo');

define ('GD_TEMPLATEID_PAGESECTIONSETTINGSID_ADDONS', 'addons');
define ('GD_TEMPLATEID_PAGESECTIONSETTINGSID_MAIN', 'main');
define ('GD_TEMPLATEID_PAGESECTIONSETTINGSID_SIDEINFO', 'sideinfo');
define ('GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWMAIN', 'quickview-main');
define ('GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWSIDEINFO', 'quickview-sideinfo');

/**---------------------------------------------------------------------------------------------------------------
 * All PageSections
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATE_PAGESECTION_HOVER', PoP_ServerUtils::get_template_definition('hover', true));
define ('GD_TEMPLATE_PAGESECTION_NAVIGATOR', PoP_ServerUtils::get_template_definition('navigator', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONS_HOME', PoP_ServerUtils::get_template_definition('addons-home', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONS_TAG', PoP_ServerUtils::get_template_definition('addons-tag', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONS_PAGE', PoP_ServerUtils::get_template_definition('addons-page', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE', PoP_ServerUtils::get_template_definition('addons-single', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR', PoP_ServerUtils::get_template_definition('addons-author', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONS_404', PoP_ServerUtils::get_template_definition('addons-404', true));

define ('GD_TEMPLATE_PAGESECTION_HOME', PoP_ServerUtils::get_template_definition('home', true));
define ('GD_TEMPLATE_PAGESECTION_TAG', PoP_ServerUtils::get_template_definition('tag', true));
define ('GD_TEMPLATE_PAGESECTION_PAGE', PoP_ServerUtils::get_template_definition('page', true));
define ('GD_TEMPLATE_PAGESECTION_SINGLE', PoP_ServerUtils::get_template_definition('single', true));
define ('GD_TEMPLATE_PAGESECTION_AUTHOR', PoP_ServerUtils::get_template_definition('author', true));
define ('GD_TEMPLATE_PAGESECTION_404', PoP_ServerUtils::get_template_definition('error404', true));
define ('GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME', PoP_ServerUtils::get_template_definition('quickview-home', true));
define ('GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG', PoP_ServerUtils::get_template_definition('quickview-tag', true));
define ('GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE', PoP_ServerUtils::get_template_definition('quickview-page', true));
define ('GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE', PoP_ServerUtils::get_template_definition('quickview-single', true));
define ('GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR', PoP_ServerUtils::get_template_definition('quickview-author', true));
define ('GD_TEMPLATE_PAGESECTION_QUICKVIEW404', PoP_ServerUtils::get_template_definition('quickview-error404', true));

define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG', PoP_ServerUtils::get_template_definition('sideinfo-tag', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE', PoP_ServerUtils::get_template_definition('sideinfo-page', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME', PoP_ServerUtils::get_template_definition('sideinfo-home', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE', PoP_ServerUtils::get_template_definition('sideinfo-single', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR', PoP_ServerUtils::get_template_definition('sideinfo-author', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY', PoP_ServerUtils::get_template_definition('sideinfo-empty', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG', PoP_ServerUtils::get_template_definition('quickview-sideinfo-tag', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE', PoP_ServerUtils::get_template_definition('quickview-sideinfo-page', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME', PoP_ServerUtils::get_template_definition('quickview-sideinfo-home', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE', PoP_ServerUtils::get_template_definition('quickview-sideinfo-single', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR', PoP_ServerUtils::get_template_definition('quickview-sideinfo-author', true));
define ('GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY', PoP_ServerUtils::get_template_definition('quickview-sideinfo-empty', true));

class GD_Template_Processor_CustomTabPanePageSections extends GD_Template_Processor_TabPanePageSectionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_PAGESECTION_HOVER,
			GD_TEMPLATE_PAGESECTION_NAVIGATOR,
			GD_TEMPLATE_PAGESECTION_ADDONS_HOME,
			GD_TEMPLATE_PAGESECTION_ADDONS_TAG,
			GD_TEMPLATE_PAGESECTION_ADDONS_PAGE,
			GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE,
			GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR,
			GD_TEMPLATE_PAGESECTION_ADDONS_404,
			GD_TEMPLATE_PAGESECTION_HOME,
			GD_TEMPLATE_PAGESECTION_TAG,
			GD_TEMPLATE_PAGESECTION_PAGE,
			GD_TEMPLATE_PAGESECTION_SINGLE,
			GD_TEMPLATE_PAGESECTION_AUTHOR,
			GD_TEMPLATE_PAGESECTION_404,
			GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME,
			GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG,
			GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE,
			GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE,
			GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR,
			GD_TEMPLATE_PAGESECTION_QUICKVIEW404,

			GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR,
			GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY,
		);
	}

	
	function get_extra_intercept_urls($template_id, $atts) {

		$ret = parent::get_extra_intercept_urls($template_id, $atts);

		switch ($template_id) {
	
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR:
			
				// When not passing a tab, it will execute the default one. So for the default one, when intercepting,
				// we must also pass the URL with the tab in the query, so that if the user click on the top bar with the link having the tab="" param
				// it will open this already pre-loaded tabPane.
				$vars = GD_TemplateManager_Utils::get_vars();
				if (!$vars['tab']) {

					$url = GD_TemplateManager_Utils::get_current_url();
					$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
					$url = GD_TemplateManager_Utils::add_tab($url, $page_id);
					$authors = array(
						GD_TEMPLATE_PAGESECTION_AUTHOR,
						GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR,
					);
					if (in_array($template_id, $authors)) {
						
						// Allow URE to add the Organization/Community content source attribute
						$url = apply_filters('GD_Template_Processor_TabPanePageSections:get_extra_intercept_urls:author', $url);
					}
					$ret[$template_id] = array(
						$url
					);
				}
				break;
		}

		return $ret;
	}

	function get_default_replicate_type($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_HOVER:

				return GD_CONSTANT_REPLICATETYPE_SINGLE;
		}

		return parent::get_default_replicate_type($template_id);
	}

	function replicate_toplevel($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_HOVER:
			case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONS_404:
			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_404:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEW404:

				return true;
		}

		return parent::replicate_toplevel($template_id);
	}

	// protected function get_atts_hierarchy_initial($template_id) {

	// 	$atts = parent::get_atts_hierarchy_initial($template_id);

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_PAGESECTION_NAVIGATOR:

	// 			// Whenever clicking a link inside of the tabPanel, the interceptor must be found for the Navigator Container
	// 			$this->merge_att($template_id, $atts, 'params', array(
	// 				'data-intercept-target' => GD_INTERCEPT_TARGET_NAVIGATOR
	// 			));
	// 			break;
	// 	}

	// 	return $atts;
	// }

	protected function get_atts_block_initial($template_id, $subcomponent) {

		$ret = parent::get_atts_block_initial($template_id, $subcomponent);
	
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY:

				// Allow the Sideinfo's permanent Events Calendar to be lazy-load
				$ret = apply_filters('GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:sideinfo', $ret, $subcomponent, $this);
				break;

			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_EMPTY:

				// Allow for compatibility for the Users Carousel in the Homepage to not be lazy-load
				$ret = apply_filters('GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:main', $ret, $subcomponent, $this, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_HOVER:

				$ret = apply_filters('GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:hover', $ret, $subcomponent, $this);
				break;
			
			case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONS_404:

				// Override the style of all the form submit buttons
				$this->add_general_att($ret, 'btn-submit-class', 'btn btn-warning btn-block');
				$this->add_general_att($ret, 'alert-class', 'alert alert-warning');

				// No style for the "Publish" button box when inside the Addons
				$this->add_general_att($ret, 'formcomponent-publish-class', 'publishbox');

				// Override the style of forms for the Addons (eg: WebPost Create/Update)
				$this->add_general_att($ret, 'form-row-class', 'form-addons form-row row');
				$this->add_general_att($ret, 'form-left-class', 'form-addons form-leftside col-sm-8');
				$this->add_general_att($ret, 'form-right-class', 'form-addons form-rightside col-sm-4');

				// Make the widgets collapsible
				$this->add_general_att($ret, 'widget-collapsible', true);
				$this->add_general_att($ret, 'widget-collapsible-open', false);

				// Style of the widgets
				$this->add_general_att($ret, 'form-widget-class', 'panel panel-warning');

				// Editor rows
				$this->add_general_att($ret, 'editor-rows', 5);

				// $destroy_blocks = array(
				// 	GD_TEMPLATE_BLOCK_ADDCOMMENT,
				// );
				// if (in_array($subcomponent, $destroy_blocks)) {
				// 	$this->merge_block_jsmethod_att($subcomponent, &$ret, array('destroyPageOnSuccess'));
				// }

				$ret = apply_filters('GD_Template_Processor_CustomTabPanePageSections:get_atts_block_initial:addons', $ret, $subcomponent, $this);
				break;
		}

		return $ret;
	}

	function get_header_titles($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONS_404:

				return apply_filters(
					'GD_Template_Processor_CustomTabPanePageSections:get_header_titles:addons',
					array()
				);
		}

		return parent::get_header_titles($template_id);
	}

	function get_permanent_templates($template_id) {

		// Allow the Sideinfo to have a permanent Events Calendar
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY:

				return apply_filters(
					'GD_Template_Processor_CustomTabPanePageSections:get_permanent_templates:sideinfo',
					array(),
					$template_id
				);
		}

		return parent::get_permanent_templates($template_id);
	}

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_NAVIGATOR:

				// Must execute replicateTopLevel at the very top
				$this->add_jsmethod($ret, 'offcanvasToggle', 'closenavigator');
				break;

			case GD_TEMPLATE_PAGESECTION_HOVER:

				// Must execute replicateTopLevel at the very top
				// $this->add_jsmethod($ret, 'replicateSingleTopLevel', 'replicate-interceptor', true);
				$this->add_jsmethod($ret, 'offcanvasToggle', 'closehover');
				break;

			case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONS_404:

				// $this->add_jsmethod($ret, 'copyHeader', 'tab');
				$this->add_jsmethod($ret, 'windowSize', 'window-fullsize');
				$this->add_jsmethod($ret, 'windowSize', 'window-maximize');
				$this->add_jsmethod($ret, 'windowSize', 'window-minimize');
				break;

			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_404:

				$this->add_jsmethod($ret, 'customCloseModals');
				$this->add_jsmethod($ret, 'scrollHandler');
				break;

			// case GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWMAIN:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEW404:

				$this->add_jsmethod($ret, 'customQuickView');
		}

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_NAVIGATOR:
			case GD_TEMPLATE_PAGESECTION_HOVER:
			case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONS_404:
			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_404:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR:

				$this->add_jsmethod($ret, 'scrollbarVertical');
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEW404:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY:

				$this->add_jsmethod($ret, 'destroyPageOnModalClose', 'destroy-interceptor');
				break;
		}

		return $ret;
	}

	function get_id($template_id, $atts) {

		switch ($template_id) {
			case GD_TEMPLATE_PAGESECTION_HOVER:

				return GD_TEMPLATEID_PAGESECTIONID_HOVER;

			case GD_TEMPLATE_PAGESECTION_NAVIGATOR:

				return GD_TEMPLATEID_PAGESECTIONID_NAVIGATOR;

			case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONS_404:

				return GD_TEMPLATEID_PAGESECTIONID_ADDONS;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR:

				return GD_TEMPLATEID_PAGESECTIONID_SIDEINFO;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR:

				return GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWSIDEINFO;

			// case GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWMAIN:

			// 	return GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWMAIN;

			// case GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWSIDEINFO:

			// 	return GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWSIDEINFO;

			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_404:

				return GD_TEMPLATEID_PAGESECTIONID_MAIN;

			case GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEW404:

				return GD_TEMPLATEID_PAGESECTIONID_QUICKVIEWMAIN;
		}

		return parent::get_id($template_id, $atts);
	}

	function get_settings_id($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONS_404:

				return GD_TEMPLATEID_PAGESECTIONSETTINGSID_ADDONS;

			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_404:

				return GD_TEMPLATEID_PAGESECTIONSETTINGSID_MAIN;

			case GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEW404:

				return GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWMAIN;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY:

				return GD_TEMPLATEID_PAGESECTIONSETTINGSID_SIDEINFO;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY:

				return GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWSIDEINFO;
		}

		return parent::get_settings_id($template_id);
	}

	function get_frontend_mergeid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_HOVER:
			case GD_TEMPLATE_PAGESECTION_NAVIGATOR:
			case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONS_404:
			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_404:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY:

				return $this->get_frontend_id($template_id, $atts).'-merge';
		}

		return parent::get_frontend_mergeid($template_id, $atts);
	}

	function intercept_skip_state_update($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONS_404:
			// case GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWMAIN:
			// case GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWSIDEINFO:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEW404:
				
				return true;
		}

		return parent::intercept_skip_state_update($template_id);
	}

	protected function get_blocks($template_id) {

		$ret = parent::get_blocks($template_id);
		$vars = GD_TemplateManager_Utils::get_vars();

		// Main / Submains / Independent / Related Blocks
		switch ($template_id) {
				
			case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_home_blockunits($ret, $template_id);
				break;
				
			case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_tag_blockunits($ret, $template_id);
				break;
				
			case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_single_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR:
			
				PoPTheme_Wassup_PageSectionSettingsUtils::add_author_blockunits($ret, $template_id);
				break;
				
			case GD_TEMPLATE_PAGESECTION_NAVIGATOR:
			case GD_TEMPLATE_PAGESECTION_HOVER:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_page_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_ADDONS_404:
			case GD_TEMPLATE_PAGESECTION_404:
			case GD_TEMPLATE_PAGESECTION_QUICKVIEW404:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_error_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_sideinfo_tag_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_sideinfo_page_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_sideinfo_home_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_sideinfo_single_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_sideinfo_author_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_sideinfo_empty_blockunits($ret, $template_id);
				break;
		}

		// // If fetching a block, then go straight to loading corresponding block from the page
		if ($vars['fetching-json-data']) {

			return $ret;
		}

		global $gd_template_settingsmanager;
		$replicable = $blockgroupsreplicable = array();

		// Replicable: only when loading initial
		if (GD_TemplateManager_Utils::loading_frame()) {
			switch ($template_id) {

				case GD_TEMPLATE_PAGESECTION_HOVER:

					$replicable = apply_filters(
						'GD_Template_Processor_CustomTabPanePageSections:blocks:hover_replicable',
						array()
					);
					$blockgroupsreplicable = apply_filters(
						'GD_Template_Processor_CustomTabPanePageSections:blockgroups:hover_replicable',
						array(
							// GD_TEMPLATE_BLOCKGROUP_LOGIN,
						)
					);
					
					// If the current page is any of these ones, then no need to create the corresponding replicable interceptor, since the block is already created and will never be destroyed
					$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();

					// Remove the current block from replicable
					$current_block = $gd_template_settingsmanager->get_page_block($page_id);					
					$pos = array_search($current_block, $replicable);
					if ($pos !== false) {
						array_splice($replicable, $pos, 1);
					}
					else {

						// Remove the current blockgroup from replicable
						$current_blockgroup = $gd_template_settingsmanager->get_page_blockgroup($page_id);					
						$pos = array_search($current_blockgroup, $replicable);
						if ($pos !== false) {
							array_splice($replicable, $pos, 1);
						}
					}
					break;

				case GD_TEMPLATE_PAGESECTION_ADDONS_HOME:
				case GD_TEMPLATE_PAGESECTION_ADDONS_TAG:
				case GD_TEMPLATE_PAGESECTION_ADDONS_PAGE:
				case GD_TEMPLATE_PAGESECTION_ADDONS_404:
				case GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE:
				case GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR:

					$replicable = apply_filters(
						'GD_Template_Processor_CustomTabPanePageSections:blocks:addons_replicable',
						array()
					);
					$blockgroupsreplicable = apply_filters(
						'GD_Template_Processor_CustomTabPanePageSections:blockgroups:addons_replicable',
						array(
							// GD_TEMPLATE_BLOCKGROUP_REPLICATEADDCOMMENT,
						)
					);
					break;

				case GD_TEMPLATE_PAGESECTION_HOME:
				case GD_TEMPLATE_PAGESECTION_TAG:
				case GD_TEMPLATE_PAGESECTION_PAGE:
				case GD_TEMPLATE_PAGESECTION_404:
				case GD_TEMPLATE_PAGESECTION_SINGLE:
				case GD_TEMPLATE_PAGESECTION_AUTHOR:
				
					$replicable = apply_filters(
						'GD_Template_Processor_CustomTabPanePageSections:blocks:main_replicable',
						array()
					);
					$blockgroupsreplicable = apply_filters(
						'GD_Template_Processor_CustomTabPanePageSections:blockgroups:main_replicable',
						array()
					);
					break;

				case GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY:
				case GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG:
				case GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE:
				case GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME:
				case GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE:
				case GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR:

					$replicable = apply_filters(
						'GD_Template_Processor_CustomTabPanePageSections:blocks:sideinfo_replicable',
						array()
					);
					$blockgroupsreplicable = apply_filters(
						'GD_Template_Processor_CustomTabPanePageSections:blockgroups:sideinfo_replicable',
						array()
					);
					break;
			}
		}

		// Merge all blocks with the ones set by the parent
		$this->add_blocks($ret, $replicable, GD_TEMPLATEBLOCKSETTINGS_REPLICABLE);
		$this->add_blockgroups($ret, $blockgroupsreplicable, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE);

		return $ret;
	}

	function get_blockunit_intercept_url($template_id, $blockunit, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_HOME:
			case GD_TEMPLATE_PAGESECTION_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGE:
			case GD_TEMPLATE_PAGESECTION_SINGLE:
			case GD_TEMPLATE_PAGESECTION_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_404:

				$source_blocks = apply_filters(
					'GD_Template_Processor_CustomTabPanePageSections:blockunit_intercept_url:main_sourceblocks',
					array()
				);
				if ($source_block = $source_blocks[$blockunit]) {

					$source_processor = $gd_template_processor_manager->get_processor($source_block);
					return $source_processor->get_dataload_source($source_block, $atts);
				}
				break;

			case GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE:
			case GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR:

				$source_blocks = apply_filters(
					'GD_Template_Processor_CustomTabPanePageSections:blockunit_intercept_url:sideinfo_sourceblocks',
					array()
				);
				if ($source_block = $source_blocks[$blockunit]) {

					$source_processor = $gd_template_processor_manager->get_processor($source_block);
					return $source_processor->get_dataload_source($source_block, $atts);
				}
				break;
		}
		
		return parent::get_blockunit_intercept_url($template_id, $blockunit, $atts);
	}

	
	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager, $post;

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_404:	

				$ret['title'] = __('Ops, there\'s nothing here!', 'poptheme-wassup');
				$ret['message'] = sprintf(
					__('<p>We couldn\'t find anything under this URL...</p>', 'poptheme-wassup').'<ul class="nav pull-left clearfix"><li>%s</li><li>%s</li><li>%s</li><li>%s</li><li>%s</li></ul>',
					sprintf(
						'<a href="%s">%s</a>',
						get_bloginfo('siteurl'),
						get_the_title(POPTHEME_WASSUP_PAGEPLACEHOLDER_HOME)
					),
					sprintf(
						'<a href="%s">%s</a>',
						get_permalink(POP_WPAPI_PAGE_ALLCONTENT),
						get_the_title(POP_WPAPI_PAGE_ALLCONTENT)
					),
					sprintf(
						'<a href="%s">%s</a>',
						get_permalink(POP_WPAPI_PAGE_ALLUSERS),
						get_the_title(POP_WPAPI_PAGE_ALLUSERS)
					),
					sprintf(
						'<a href="%s">%s</a>',
						get_permalink(POP_WPAPI_PAGE_SEARCHPOSTS),
						get_the_title(POP_WPAPI_PAGE_SEARCHPOSTS)
					),
					sprintf(
						'<a href="%s">%s</a>',
						get_permalink(POP_WPAPI_PAGE_SEARCHUSERS),
						get_the_title(POP_WPAPI_PAGE_SEARCHUSERS)
					)
				);
				break;
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomTabPanePageSections();
