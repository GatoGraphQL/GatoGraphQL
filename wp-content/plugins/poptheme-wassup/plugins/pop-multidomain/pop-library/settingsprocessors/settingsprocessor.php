<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_MultiDomain_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function silent_document($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => true,
			);
		}

		return parent::silent_document($hierarchy);
	}

	function is_appshell($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => true,
			);
		}

		return parent::is_appshell($hierarchy);
	}

	function store_local($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => true,
			);
		}

		return parent::store_local($hierarchy);
	}

	function get_checkpoints($hierarchy) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => Wassup_MultiDomain_SettingsProcessor_CheckpointUtils::get_checkpoint($hierarchy, WASSUP_CHECKPOINT_DOMAINVALID),//$profile_datafromserver,
			);
		}
	
		return parent::get_checkpoints($hierarchy);
	}

	function get_page_blockgroups($hierarchy/*, $include_common = true*/) {

		$ret = array();

		// Common BlockGroups
		// if ($include_common) {
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			// Default
			$pageblockgroups_allothers = array(

				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => GD_TEMPLATE_BLOCKGROUP_INITIALIZEDOMAIN,
			);
			foreach ($pageblockgroups_allothers as $page => $blockgroup) {
				$ret[$page]['blockgroups']['default'] = $blockgroup;
			}
		}

		return $ret;
	}

	function get_page_blocks($hierarchy/*, $include_common = true*/) {

		$ret = array();

		// $include_common: used to tell if we include also the common blocks in the response.
		// These common blocks are needed to produce the dataload-source of, eg, the Navigator Blocks, even
		// when first loading the website on an Author or Single page. Without the Navigator blocks placed in
		// common, then we can't get their dataload-source.
		// However, when generating the cache (file generator.php) these are not needed, so then skip them
		// Common blocks
		// if ($include_common) {
		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			// Default
			$pageblocks_allothers = array(

				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => null,
			);
			foreach ($pageblocks_allothers as $page => $block) {
				$ret[$page]['blocks']['default'] = $block;
			}

			// Actions 
			$pageactions = array(
				POP_MULTIDOMAIN_PAGE_LOADERS_INITIALIZEDOMAIN => null,
			);
			foreach ($pageactions as $page => $action) {
				$ret[$page]['action'] = $action;
			}
		}
		
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_MultiDomain_Template_SettingsProcessor();
