<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class AgendaUrbana_BlockGroupHooks {

	function __construct() {

		// Change the Blockgroups to show on the Homepage
		add_filter(
			'GD_Template_Processor_CustomBlockGroups:hometop:blockgroups:widget',
			array($this, 'hometop_widget')
		);
	}

	function hometop_widget($blockgroup) {

		// Add the Blockgroup which has the Featured widget
		if (AGENDAURBANA_CAT_FEATURED) {

			$cat_blockgroups = array(
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS00 => GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA_CATEGORYPOSTS00,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS01 => GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA_CATEGORYPOSTS01,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS02 => GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA_CATEGORYPOSTS02,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS03 => GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA_CATEGORYPOSTS03,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS04 => GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA_CATEGORYPOSTS04,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS05 => GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA_CATEGORYPOSTS05,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS06 => GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA_CATEGORYPOSTS06,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS07 => GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA_CATEGORYPOSTS07,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS08 => GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA_CATEGORYPOSTS08,
				POPTHEME_WASSUP_CATEGORYPROCESSORS_CAT_CATEGORYPOSTS09 => GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA_CATEGORYPOSTS09,
			);
			if ($cat_blockgroup = $cat_blockgroups[AGENDAURBANA_CAT_FEATURED]) {
				return $cat_blockgroup;
			}
		}

		return $blockgroup;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new AgendaUrbana_BlockGroupHooks();
