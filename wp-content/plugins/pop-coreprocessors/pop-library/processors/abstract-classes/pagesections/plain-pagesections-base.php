<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PlainPageSectionsBase extends GD_Template_Processor_PageSectionsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_PAGESECTION_PLAIN;
	}

	protected function get_initjs_blockbranches($template_id, $atts) {

		$ret = parent::get_initjs_blockbranches($template_id, $atts);

		$id = $this->get_frontend_mergeid($template_id, $atts);
		$ret[] = '#'.$id.' > div.pop-block';

		return $ret;
	}
}

