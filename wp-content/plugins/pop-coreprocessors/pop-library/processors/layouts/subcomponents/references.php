<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_REFERENCES_LINE', PoP_TemplateIDUtils::get_template_definition('layout-references-line'));
define ('GD_TEMPLATE_LAYOUT_REFERENCES_RELATED', PoP_TemplateIDUtils::get_template_definition('layout-references-related'));
define ('GD_TEMPLATE_LAYOUT_REFERENCES_ADDONS', PoP_TemplateIDUtils::get_template_definition('layout-references-addons'));
define ('GD_TEMPLATE_LAYOUT_REFERENCES_REFERENCEDPOSTTITLE', PoP_TemplateIDUtils::get_template_definition('layout-references-referencedposttitle'));
define ('GD_TEMPLATE_LAYOUT_REFERENCES_AUTHORREFERENCEDPOSTTITLE', PoP_TemplateIDUtils::get_template_definition('layout-references-authorreferencedposttitle'));

class GD_Template_Processor_ReferencesLayouts extends GD_Template_Processor_ReferencesLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_LAYOUT_REFERENCES_LIST,
			GD_TEMPLATE_LAYOUT_REFERENCES_LINE,
			GD_TEMPLATE_LAYOUT_REFERENCES_RELATED,
			GD_TEMPLATE_LAYOUT_REFERENCES_ADDONS,
			GD_TEMPLATE_LAYOUT_REFERENCES_REFERENCEDPOSTTITLE,
			GD_TEMPLATE_LAYOUT_REFERENCES_AUTHORREFERENCEDPOSTTITLE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_REFERENCES_REFERENCEDPOSTTITLE:
				
				$ret[] = GD_TEMPLATE_CODE_REFERENCEDAFTERREADING;
				$ret[] = GD_TEMPLATE_LAYOUT_POSTTITLE;
				break;
			
			case GD_TEMPLATE_LAYOUT_REFERENCES_AUTHORREFERENCEDPOSTTITLE:
				
				$ret[] = GD_TEMPLATE_CODE_AUTHORREFERENCEDAFTERREADING;
				$ret[] = GD_TEMPLATE_LAYOUT_POSTTITLE;
				break;
			
			default:
				
				$layouts = array(
					// GD_TEMPLATE_LAYOUT_REFERENCES_LIST => GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LIST,
					GD_TEMPLATE_LAYOUT_REFERENCES_LINE => GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LINE,
					GD_TEMPLATE_LAYOUT_REFERENCES_RELATED => GD_TEMPLATE_LAYOUT_MULTIPLEPOST_RELATED,
					GD_TEMPLATE_LAYOUT_REFERENCES_ADDONS => GD_TEMPLATE_LAYOUT_MULTIPLEPOST_ADDONS,
				);
				if ($layout = $layouts[$template_id]) {

					$ret[] = $layout;
				}
				break;
		}

		return $ret;
	}

	function get_html_tag($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_REFERENCES_REFERENCEDPOSTTITLE:
			case GD_TEMPLATE_LAYOUT_REFERENCES_AUTHORREFERENCEDPOSTTITLE:
				
				return 'span';
		}
	
		return parent::get_html_tag($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ReferencesLayouts();