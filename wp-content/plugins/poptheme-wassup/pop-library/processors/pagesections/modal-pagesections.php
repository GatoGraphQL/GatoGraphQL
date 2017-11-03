<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * All IDs
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATEID_PAGESECTIONID_MODALS', 'ps-modals');
define ('GD_TEMPLATEID_PAGESECTIONSETTINGSID_MODALS', 'modals');

/**---------------------------------------------------------------------------------------------------------------
 * All PageSections
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATE_PAGESECTION_MODALS_HOME', PoP_TemplateIDUtils::get_template_definition('modals-home', true));
define ('GD_TEMPLATE_PAGESECTION_MODALS_TAG', PoP_TemplateIDUtils::get_template_definition('modals-tag', true));
define ('GD_TEMPLATE_PAGESECTION_MODALS_PAGE', PoP_TemplateIDUtils::get_template_definition('modals-page', true));
define ('GD_TEMPLATE_PAGESECTION_MODALS_SINGLE', PoP_TemplateIDUtils::get_template_definition('modals-single', true));
define ('GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR', PoP_TemplateIDUtils::get_template_definition('modals-author', true));
define ('GD_TEMPLATE_PAGESECTION_MODALS_404', PoP_TemplateIDUtils::get_template_definition('modals-404', true));

class GD_Template_Processor_CustomModalPageSections extends GD_Template_Processor_ModalPageSectionsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_PAGESECTION_MODALS_HOME,
			GD_TEMPLATE_PAGESECTION_MODALS_TAG,
			GD_TEMPLATE_PAGESECTION_MODALS_PAGE,
			GD_TEMPLATE_PAGESECTION_MODALS_SINGLE,
			GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR,
			GD_TEMPLATE_PAGESECTION_MODALS_404,
		);
	}

	protected function get_atts_block_initial($template_id, $subcomponent) {

		$ret = parent::get_atts_block_initial($template_id, $subcomponent);
	
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				$ret = apply_filters('GD_Template_Processor_CustomModalPageSections:get_atts_block_initial:modals', $ret, $subcomponent, $this);
				break;
		}

		return $ret;
	}

	function replicate_toplevel($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				return true;
		}

		return parent::replicate_toplevel($template_id);
	}

	function get_default_replicate_type($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				return GD_CONSTANT_REPLICATETYPE_SINGLE;
		}

		return parent::get_default_replicate_type($template_id);
	}

	function get_replicate_types($template_id) {

		$ret = parent::get_replicate_types($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				$ret = apply_filters('GD_Template_Processor_CustomModalPageSections:get_replicate_types:modals', $ret);
				break;
		}

		return $ret;
	}

	function unique_urls($template_id) {

		$ret = parent::unique_urls($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				$ret = apply_filters('GD_Template_Processor_CustomModalPageSections:unique_urls:modals', $ret);
				break;
		}

		return $ret;
	}

	function get_header_titles($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				return apply_filters(
					'GD_Template_Processor_CustomModalPageSections:get_header_titles:modals',
					array(
					)
				);
		}

		return parent::get_header_titles($template_id);
	}

	function get_dialog_classes($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				return apply_filters(
					'GD_Template_Processor_CustomModalPageSections:get_dialog_classes:modals',
					array(
					)
				);
		}

		return parent::get_header_titles($template_id);
	}


	function get_body_classes($template_id) {

		$ret = parent::get_body_classes($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				$ret = apply_filters(
					'GD_Template_Processor_CustomModalPageSections:get_body_classes:modals',
					$ret
				);
		}

		return $ret;
	}

	function get_id($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				return GD_TEMPLATEID_PAGESECTIONID_MODALS;
		}

		return parent::get_id($template_id, $atts);
	}

	function get_settings_id($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				return GD_TEMPLATEID_PAGESECTIONSETTINGSID_MODALS;
		}

		return parent::get_settings_id($template_id);
	}

	function intercept_skip_state_update($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			case GD_TEMPLATE_PAGESECTION_MODALS_404:
				
				return true;
		}

		return parent::intercept_skip_state_update($template_id);
	}

	protected function get_blocks($template_id) {

		$ret = parent::get_blocks($template_id);
		$vars = GD_TemplateManager_Utils::get_vars();

		// Main / Submains / Independent / Related Blocks
		switch ($template_id) {
				
			case GD_TEMPLATE_PAGESECTION_MODALS_HOME:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_home_blockunits($ret, $template_id);
				break;
				
			case GD_TEMPLATE_PAGESECTION_MODALS_TAG:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_tag_blockunits($ret, $template_id);
				break;
				
			case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_single_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:
			
				PoPTheme_Wassup_PageSectionSettingsUtils::add_author_blockunits($ret, $template_id);
				break;
				
			case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_page_blockunits($ret, $template_id);
				break;

			case GD_TEMPLATE_PAGESECTION_MODALS_404:

				PoPTheme_Wassup_PageSectionSettingsUtils::add_error_blockunits($ret, $template_id);
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

				case GD_TEMPLATE_PAGESECTION_MODALS_HOME:
				case GD_TEMPLATE_PAGESECTION_MODALS_TAG:
				case GD_TEMPLATE_PAGESECTION_MODALS_PAGE:
				case GD_TEMPLATE_PAGESECTION_MODALS_404:
				case GD_TEMPLATE_PAGESECTION_MODALS_SINGLE:
				case GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR:

					$replicable = apply_filters(
						'GD_Template_Processor_CustomModalPageSections:blocks:modals_replicable',
						array()
					);
					$blockgroupsreplicable = apply_filters(
						'GD_Template_Processor_CustomModalPageSections:blockgroups:modals_replicable',
						array(
						)
					);
					break;
			}
		}

		// Merge all blocks with the ones set by the parent
		$this->add_blocks($ret, $replicable, GD_TEMPLATEBLOCKSETTINGS_REPLICABLE);
		$this->add_blockgroups($ret, $blockgroupsreplicable, GD_TEMPLATEBLOCKSETTINGS_BLOCKGROUPREPLICABLE);

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomModalPageSections();
