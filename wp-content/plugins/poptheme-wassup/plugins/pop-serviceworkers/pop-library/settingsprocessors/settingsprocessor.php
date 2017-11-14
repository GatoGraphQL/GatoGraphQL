<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_PoPSW_Template_SettingsProcessor extends GD_Template_SettingsProcessorBase {

	function silent_document($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_SERVICEWORKERS_PAGE_APPSHELL => true,
			);
		}

		return parent::silent_document($hierarchy);
	}

	function is_appshell($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_SERVICEWORKERS_PAGE_APPSHELL => true,
			);
		}

		return parent::is_appshell($hierarchy);
	}

	// Comment Leo 14/11/2017: no need to comment it anymore, since making the AppShell always be loading using 'resource',
	// Then we don't need to obtain its bundle(group) files, we just load all individual resources directly
	// // Comment Leo 13/11/2017: The AppShell page cannot be marked as for internal use, or
	// // we won't be able to generate the bundle(group)s to load it
	function is_for_internal_use($hierarchy) {

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {

			return array(
				POP_SERVICEWORKERS_PAGE_APPSHELL => true,
			);
		}

		return parent::is_for_internal_use($hierarchy);
	}

	function get_page_blocks($hierarchy/*, $include_common = true*/) {

		$ret = array();

		if ($hierarchy == GD_SETTINGS_HIERARCHY_PAGE) {
	
			$pageblocks = array(
				POP_SERVICEWORKERS_PAGE_APPSHELL  => GD_TEMPLATE_BLOCK_APPSHELL,
			);
			foreach ($pageblocks as $page => $block) {
				$ret[$page]['blocks']['default'] = $block;
			}
		}

		return $ret;	
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_PoPSW_Template_SettingsProcessor();
