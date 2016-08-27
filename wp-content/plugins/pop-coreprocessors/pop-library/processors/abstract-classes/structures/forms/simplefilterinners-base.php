<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SimpleFilterInnersBase extends GD_Template_Processor_FilterInnersBase {

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	function get_filter_component($template_id, $filtercomponent) {

		// Return the filterformcomponent for a simpler filter, as used in the sideinfo.
		return $filtercomponent->get_filterformcomponent();
	}

	function get_submitbtn_template($template_id) {

		return GD_TEMPLATE_SUBMITBUTTON_SEARCH;
	}
}
