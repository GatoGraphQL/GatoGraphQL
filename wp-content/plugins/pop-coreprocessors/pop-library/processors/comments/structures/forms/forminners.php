<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_ADDCOMMENT', PoP_TemplateIDUtils::get_template_definition('forminner-addcomment'));

class GD_Template_Processor_CommentsFormInners extends GD_Template_Processor_FormInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMINNER_ADDCOMMENT
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_ADDCOMMENT:

				$ret = array_merge(
					$ret,
					array(
						GD_TEMPLATE_FORMCOMPONENTGROUP_COMMENTEDITOR,
						GD_TEMPLATE_FORMCOMPONENT_COMMENTID,
						GD_TEMPLATE_FORMCOMPONENT_POSTID,
						GD_TEMPLATE_SUBMITBUTTON_SUBMIT,
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
new GD_Template_Processor_CommentsFormInners();