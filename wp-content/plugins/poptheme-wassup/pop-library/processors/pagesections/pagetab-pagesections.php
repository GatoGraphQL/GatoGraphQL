<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * All IDs
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATEID_PAGESECTIONSETTINGSID_ADDONTABS', 'addontabs');
define ('GD_TEMPLATEID_PAGESECTIONID_ADDONTABS', 'ps-addontabs');

define ('GD_TEMPLATEID_PAGESECTIONID_PAGETABS', 'ps-pagetabs');
define ('GD_TEMPLATEID_PAGESECTIONSETTINGSID_PAGETABS', 'pagetabs');

/**---------------------------------------------------------------------------------------------------------------
 * All PageSections
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME', PoP_TemplateIDUtils::get_template_definition('addontabs-home', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG', PoP_TemplateIDUtils::get_template_definition('addontabs-tag', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE', PoP_TemplateIDUtils::get_template_definition('addontabs-page', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE', PoP_TemplateIDUtils::get_template_definition('addontabs-single', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR', PoP_TemplateIDUtils::get_template_definition('addontabs-author', true));
define ('GD_TEMPLATE_PAGESECTION_ADDONTABS_404', PoP_TemplateIDUtils::get_template_definition('addontabs-404', true));

define ('GD_TEMPLATE_PAGESECTION_PAGETABS_HOME', PoP_TemplateIDUtils::get_template_definition('pagetabs-home', true));
define ('GD_TEMPLATE_PAGESECTION_PAGETABS_TAG', PoP_TemplateIDUtils::get_template_definition('pagetabs-tag', true));
define ('GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE', PoP_TemplateIDUtils::get_template_definition('pagetabs-page', true));
define ('GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE', PoP_TemplateIDUtils::get_template_definition('pagetabs-single', true));
define ('GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR', PoP_TemplateIDUtils::get_template_definition('pagetabs-author', true));
define ('GD_TEMPLATE_PAGESECTION_PAGETABS_404', PoP_TemplateIDUtils::get_template_definition('pagetabs-404', true));

class GD_Template_Processor_CustomPageTabPageSections extends GD_Template_Processor_PageTabPageSectionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME,
			GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG,
			GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE,
			GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE,
			GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR,
			GD_TEMPLATE_PAGESECTION_ADDONTABS_404,
			GD_TEMPLATE_PAGESECTION_PAGETABS_HOME,
			GD_TEMPLATE_PAGESECTION_PAGETABS_TAG,
			GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE,
			GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE,
			GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR,
			GD_TEMPLATE_PAGESECTION_PAGETABS_404,
		);
	}
	
	function get_extra_intercept_urls($template_id, $atts) {

		$ret = parent::get_extra_intercept_urls($template_id, $atts);

		switch ($template_id) {
	
			case GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR:
			
				// When not passing a tab, it will execute the default one. So for the default one, when intercepting,
				// we must also pass the URL with the tab in the query, so that if the user click on the top bar with the link having the tab="" param
				// it will open this already pre-loaded tabPane.
				$vars = GD_TemplateManager_Utils::get_vars();
				if (!$vars['tab']) {

					$url = GD_TemplateManager_Utils::get_current_url();
					$page_id = GD_TemplateManager_Utils::get_hierarchy_page_id();
					$url = GD_TemplateManager_Utils::add_tab($url, $page_id);
					if ($template_id == GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR) {
						
						// Allow URE to add the Organization/Community content source attribute
						$url = apply_filters('GD_Template_Processor_PageTabPageSections:get_extra_intercept_urls:author', $url);
					}

					// Comment Leo 10/06/2017: With handlebars 4.0.10, function #each has a bug: if provided an array with only 1 element,
					// it doesn't advance the context (it was failing in line "{{#each .}}" in files wp-content/plugins/pop-bootstrapprocessors/js/templates/pagesections/pagesection-pagetab.tmpl pagesection-tabpane.tmpl and pagesection-modal.tmpl - that line is supposed to be on the 5th path down the context, but it was actually found only on the 4th after the #each)
					// To fix the problem, pass an object instead of an array, and it works fine
					// Reported bug: https://github.com/wycats/handlebars.js/issues/1300
					// // Comment Leo 10/06/2017: switched back to handlebars 4.0.5, so can comment the key below
					$ret[$template_id] = array(
						'a' => $url,
					);
				}
				break;
		}

		return $ret;
	}
	
	function get_id($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_404:

				return GD_TEMPLATEID_PAGESECTIONID_ADDONTABS;

			case GD_TEMPLATE_PAGESECTION_PAGETABS_HOME:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_404:

				return GD_TEMPLATEID_PAGESECTIONID_PAGETABS;
		}

		return parent::get_id($template_id, $atts);
	}

	function get_frontend_mergeid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_404:

			case GD_TEMPLATE_PAGESECTION_PAGETABS_HOME:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_404:

				return $this->get_frontend_id($template_id, $atts).'-merge';
		}

		return parent::get_frontend_mergeid($template_id, $atts);
	}

	function get_settings_id($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_404:
			
				return GD_TEMPLATEID_PAGESECTIONSETTINGSID_ADDONTABS;

			case GD_TEMPLATE_PAGESECTION_PAGETABS_HOME:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_404:
		
				return GD_TEMPLATEID_PAGESECTIONSETTINGSID_PAGETABS;
		}

		return parent::get_settings_id($template_id);
	}

	protected function get_blocks($template_id) {

		$ret = parent::get_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_pagetab_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_HOME:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_hometab_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_TAG:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_tagtab_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_singletab_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_authortab_blockunits($ret, $template_id);
				break;
				
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_404:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_404:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_errortab_blockunits($ret, $template_id);
				break;
		}
		
		// Replicable: only when loading initial
		if (GD_TemplateManager_Utils::loading_frame()) {

			$replicable = $blockgroupsreplicable = array();

			switch ($template_id) {

				case GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME:
				case GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG:
				case GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE:
				case GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE:
				case GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR:
				case GD_TEMPLATE_PAGESECTION_ADDONTABS_404:

					$replicable = apply_filters(
						'GD_Template_Processor_CustomPageTabPageSections:blocks:replicable_pagetabs:addontabs', 
						array(
							// GD_TEMPLATE_BLOCK_ADDONTABS_ADDCOMMENT
						)
					);
					$blockgroupsreplicable = apply_filters(
						'GD_Template_Processor_CustomPageTabPageSections:blockgroups:replicable_pagetabs:addontabs', 
						array()
					);
					break;

				case GD_TEMPLATE_PAGESECTION_PAGETABS_HOME:
				case GD_TEMPLATE_PAGESECTION_PAGETABS_TAG:
				case GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE:
				case GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE:
				case GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR:
				case GD_TEMPLATE_PAGESECTION_PAGETABS_404:

					$replicable = apply_filters(
						'GD_Template_Processor_CustomPageTabPageSections:blocks:replicable_pagetabs:main', 
						array()
					);
					$blockgroupsreplicable = apply_filters(
						'GD_Template_Processor_CustomPageTabPageSections:blockgroups:replicable_pagetabs:main', 
						array()
					);
					break;
			}

			// Merge all blocks with the ones set by the parent
			$this->add_blocks($ret, $replicable, GD_TEMPLATEBLOCKSETTINGS_REPLICABLE);
			$this->add_blockgroups($ret, $blockgroupsreplicable, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE);
		}

		return $ret;
	}

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_404:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_HOME:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_404:

				$this->add_jsmethod($ret, 'scrollbarHorizontal');
				break;
		}

		return $ret;
	}

	function intercept_skip_state_update($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_404:
				
				return true;
		}

		return parent::intercept_skip_state_update($template_id);
	}

	function get_btn_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_404:

				return 'btn btn-warning btn-sm';

			case GD_TEMPLATE_PAGESECTION_PAGETABS_HOME:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_404:

				return 'btn btn-inverse btn-sm';
		}

		return parent::get_btn_class($template_id, $atts);
	}

	function get_blockunit_intercept_url($template_id, $blockunit, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_ADDONTABS_404:

				$source_blocks = apply_filters(
					'GD_Template_Processor_CustomPageTabPageSections:blockunit_intercept_url:source_blocks:addontabs', 
					array(
						// GD_TEMPLATE_BLOCK_ADDONTABS_ADDCOMMENT => GD_TEMPLATE_BLOCK_ADDCOMMENT,
					)
				);
				if ($source_block = $source_blocks[$blockunit]) {

					$source_processor = $gd_template_processor_manager->get_processor($source_block);
					return $source_processor->get_dataload_source($source_block, $atts);
				}
				break;

			case GD_TEMPLATE_PAGESECTION_PAGETABS_HOME:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_TAG:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_PAGETABS_404:

				$source_blocks = apply_filters(
					'GD_Template_Processor_CustomPageTabPageSections:blockunit_intercept_url:source_blocks:main', 
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
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomPageTabPageSections();
