<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_LINKCATEGORIES', PoP_TemplateIDUtils::get_template_definition('formcomponent-linkcategories'));
define ('GD_TEMPLATE_FORMCOMPONENT_APPLIESTO', PoP_TemplateIDUtils::get_template_definition('formcomponent-appliesto'));
define ('GD_TEMPLATE_FORMCOMPONENT_CATEGORIES', PoP_TemplateIDUtils::get_template_definition('formcomponent-categories'));
// define ('GD_TEMPLATE_FORMCOMPONENT_SECTIONS', PoP_TemplateIDUtils::get_template_definition('formcomponent-sections'));
define ('GD_TEMPLATE_FORMCOMPONENT_WEBPOSTSECTIONS', PoP_TemplateIDUtils::get_template_definition('formcomponent-webpostsections'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_LINKCATEGORIES', PoP_TemplateIDUtils::get_template_definition('linkcategories', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_APPLIESTO', PoP_TemplateIDUtils::get_template_definition('appliesto', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_CATEGORIES', PoP_TemplateIDUtils::get_template_definition('categories', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_SECTIONS', PoP_TemplateIDUtils::get_template_definition('sections', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_WEBPOSTSECTIONS', PoP_TemplateIDUtils::get_template_definition('postsections', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_LINKACCESS', PoP_TemplateIDUtils::get_template_definition('linkaccess', true));

class GD_Template_Processor_CreateUpdatePostMultiSelectFormComponentInputs extends GD_Template_Processor_MultiSelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_LINKCATEGORIES,
			GD_TEMPLATE_FORMCOMPONENT_APPLIESTO,
			GD_TEMPLATE_FORMCOMPONENT_CATEGORIES,
			// GD_TEMPLATE_FORMCOMPONENT_SECTIONS,
			GD_TEMPLATE_FORMCOMPONENT_WEBPOSTSECTIONS,
			GD_TEMPLATE_FILTERFORMCOMPONENT_LINKCATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENT_APPLIESTO,
			GD_TEMPLATE_FILTERFORMCOMPONENT_CATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENT_SECTIONS,
			GD_TEMPLATE_FILTERFORMCOMPONENT_WEBPOSTSECTIONS,
			GD_TEMPLATE_FILTERFORMCOMPONENT_LINKACCESS,
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_LINKCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_CATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_SECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_WEBPOSTSECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_APPLIESTO:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_LINKACCESS:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_LINKCATEGORIES:
			case GD_TEMPLATE_FORMCOMPONENT_CATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_LINKCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_CATEGORIES:
			
				return __('Categories', 'poptheme-wassup');

			// case GD_TEMPLATE_FORMCOMPONENT_SECTIONS:
			case GD_TEMPLATE_FORMCOMPONENT_WEBPOSTSECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_SECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_WEBPOSTSECTIONS:
			
				return __('Sections', 'poptheme-wassup');

			case GD_TEMPLATE_FORMCOMPONENT_APPLIESTO:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_APPLIESTO:

				return __('Applies to', 'poptheme-wassup');
		
			case GD_TEMPLATE_FILTERFORMCOMPONENT_LINKACCESS:

				return __('Access type', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENT_LINKCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_LINKCATEGORIES:

				return new GD_FormInput_LinkCategories($options);

			case GD_TEMPLATE_FORMCOMPONENT_CATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_CATEGORIES:
			
				return new GD_FormInput_Categories($options);

			// case GD_TEMPLATE_FORMCOMPONENT_SECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_SECTIONS:
			
				return new GD_FormInput_Sections($options);

			case GD_TEMPLATE_FORMCOMPONENT_WEBPOSTSECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_WEBPOSTSECTIONS:
			
				return new GD_FormInput_WebPostSections($options);

			case GD_TEMPLATE_FORMCOMPONENT_APPLIESTO:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_APPLIESTO:

				return new GD_FormInput_AppliesTo($options);
		
			case GD_TEMPLATE_FILTERFORMCOMPONENT_LINKACCESS:
		
				return new GD_FormInput_LinkAccess($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_LINKCATEGORIES:
				
				$ret[] = array('key' => 'value', 'field' => 'linkcategories');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_CATEGORIES:
				
				$ret[] = array('key' => 'value', 'field' => 'categories');
				break;

			// case GD_TEMPLATE_FORMCOMPONENT_SECTIONS:
				
			// 	$ret[] = array('key' => 'value', 'field' => 'sections');
			// 	break;

			case GD_TEMPLATE_FORMCOMPONENT_WEBPOSTSECTIONS:
				
				// These are the post categories
				$ret[] = array('key' => 'value', 'field' => 'cats-string');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_APPLIESTO:
				
				$ret[] = array('key' => 'value', 'field' => 'appliesto');
				break;

			case GD_TEMPLATE_FILTERFORMCOMPONENT_LINKACCESS:
				
				$ret[] = array('key' => 'value', 'field' => 'linkaccess');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateUpdatePostMultiSelectFormComponentInputs();