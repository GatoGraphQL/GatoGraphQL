<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_USERAVATAR_UPDATE', PoP_ServerUtils::get_template_definition('forminner-useravatar-update'));

class PoP_UserAvatar_Template_Processor_UserFormInners extends GD_Template_Processor_FormInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMINNER_USERAVATAR_UPDATE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_USERAVATAR_UPDATE:

				$ret = array_merge(
					array(
						GD_TEMPLATE_FORMCOMPONENTGROUP_FILEUPLOAD_PICTURE,
						GD_TEMPLATE_SUBMITBUTTON_SAVE,
					)
				);
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_USERAVATAR_UPDATE:

				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE, $atts, 'load-itemobject-value', true);				
				break;
		}
		

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_UserAvatar_Template_Processor_UserFormInners();