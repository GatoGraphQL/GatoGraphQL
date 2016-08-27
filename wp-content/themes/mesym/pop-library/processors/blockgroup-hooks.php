<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class MESYM_BlockGroupHooks {

	function __construct() {

		// Change the Blockgroups to show on the Homepage and Author page
		add_filter(
			'GD_Template_Processor_CustomBlockGroups:hometop:blockgroups:widget',
			array($this, 'hometop_widget')
		);
		add_filter(
			'GD_Template_Processor_CustomBlockGroups:authortop:blockgroups:widget',
			array($this, 'authortop_widget')
		);
	}

	function hometop_widget($blockgroup) {

		return GD_TEMPLATE_BLOCKGROUP_HOME_EVENTPROJECTWIDGETAREA;
	}

	function authortop_widget($blockgroup) {

		return GD_TEMPLATE_BLOCKGROUP_AUTHOR_EVENTPROJECTWIDGETAREA;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new MESYM_BlockGroupHooks();
