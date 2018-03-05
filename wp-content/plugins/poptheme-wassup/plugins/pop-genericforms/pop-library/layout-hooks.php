<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_Wassup_GenericForms_LayoutHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_CustomPostMultipleSidebarComponents:featuredimagevolunteer:modules', 
			array($this, 'single_components')
		);
	}

	function single_components($layouts) {

		array_splice($layouts, array_search(GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER, $layouts), 0, array(GD_TEMPLATE_VIEWCOMPONENT_COMPACTBUTTONWRAPPER_POST_VOLUNTEER_BIG));
		return $layouts;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_GenericForms_LayoutHooks();
