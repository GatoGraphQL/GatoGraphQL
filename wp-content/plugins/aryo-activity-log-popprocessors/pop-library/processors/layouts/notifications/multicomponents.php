<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_AAL_TEMPLATE_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM', PoP_ServerUtils::get_template_definition('aal-multicomponent-quicklinkgroup-bottom'));

class GD_Template_Processor_MultipleComponentLayouts extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_AAL_TEMPLATE_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_AAL_TEMPLATE_MULTICOMPONENT_QUICKLINKGROUP_BOTTOM:

				$ret = array_merge(
					$ret,
					apply_filters(
						'GD_Template_Processor_MultipleComponentLayouts:modules',
						array(),
						$template_id
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
new GD_Template_Processor_MultipleComponentLayouts();