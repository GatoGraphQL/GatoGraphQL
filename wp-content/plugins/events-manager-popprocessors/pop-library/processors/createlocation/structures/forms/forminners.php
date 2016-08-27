<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_CREATELOCATION', PoP_ServerUtils::get_template_definition('em-forminner-createlocation'));

class GD_EM_Template_Processor_CreateLocationFormInners extends GD_Template_Processor_FormInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMINNER_CREATELOCATION
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_CREATELOCATION:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLAT,
						GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONLNG,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONNAME,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONADDRESS,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONTOWN,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONSTATE,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONPOSTCODE,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONREGION,
						GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONCOUNTRY,
						GD_EM_TEMPLATE_SUBMITBUTTON_ADDLOCATION,
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
new GD_EM_Template_Processor_CreateLocationFormInners();