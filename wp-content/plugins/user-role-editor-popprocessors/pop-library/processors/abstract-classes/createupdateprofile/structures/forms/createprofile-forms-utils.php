<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreatProfileFormsUtils {

	static function get_components($template_id, &$components, $processor) {

		// Add extra components
		$extra_components_communities = array(
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES,
			GD_TEMPLATE_COLLAPSIBLEDIVIDER,
		);
		// array_splice($ret, array_search(GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP, $ret)+1, 0, $extra_components_communities);
		array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_DESCRIPTION, $components)+1, 0, $extra_components_communities);
	}
}