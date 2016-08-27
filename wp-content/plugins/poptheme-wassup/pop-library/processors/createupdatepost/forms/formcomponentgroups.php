<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_TITLE', PoP_ServerUtils::get_template_definition('formcomponentgroup-cup-title'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_STATUS', PoP_ServerUtils::get_template_definition('formcomponentgroup-cup-status'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LINK', PoP_ServerUtils::get_template_definition('formcomponentgroup-link'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LINKTITLE', PoP_ServerUtils::get_template_definition('formcomponentgroup-linktitle'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LINKACCESS', PoP_ServerUtils::get_template_definition('formcomponentgroup-linkaccess'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_LINKCATEGORIES', PoP_ServerUtils::get_template_definition('formcomponentgroup-linkcategories'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO', PoP_ServerUtils::get_template_definition('formcomponentgroup-appliesto'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES', PoP_ServerUtils::get_template_definition('formcomponentgroup-categories'));
// define ('GD_TEMPLATE_FORMCOMPONENTGROUP_SECTIONS', PoP_ServerUtils::get_template_definition('formcomponentgroup-sections'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_WEBPOSTSECTIONS', PoP_ServerUtils::get_template_definition('formcomponentgroup-webpostsections'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_HIGHLIGHTEDITOR', PoP_ServerUtils::get_template_definition('formcomponent-highlighteditorgroup'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_HIGHLIGHTEDPOST', PoP_ServerUtils::get_template_definition('formcomponentgroup-selectabletypeahead-highlightedpost'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS', PoP_ServerUtils::get_template_definition('formcomponentgroup-buttongroup-webpostsections'));

define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKACCESS', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-linkaccess'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKCATEGORIES', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-linkcategories'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_APPLIESTO', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-appliesto'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_CATEGORIES', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-categories'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SECTIONS', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-sections'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_WEBPOSTSECTIONS', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-webpostsections'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_CATEGORIES', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-categories-btngroup'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_SECTIONS', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-sections-btngroup'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-webpostsections-btngroup'));

class GD_Template_Processor_CreateUpdatePostFormComponentGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_TITLE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_STATUS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LINK,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LINKTITLE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LINKACCESS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_LINKCATEGORIES,
			GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES,
			// GD_TEMPLATE_FORMCOMPONENTGROUP_SECTIONS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_WEBPOSTSECTIONS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_HIGHLIGHTEDITOR,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_HIGHLIGHTEDPOST,
			GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKCATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_APPLIESTO,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_CATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SECTIONS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_WEBPOSTSECTIONS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKACCESS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_CATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_SECTIONS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS,
		);
	}


	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_APPLIESTO:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_CATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_WEBPOSTSECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKACCESS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_CATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_SECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_APPLIESTO:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_CATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_WEBPOSTSECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKACCESS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_CATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_SECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS:

				$ret .= ' col-sm-10';
				break;
		}

		return $ret;
	}

	function get_component($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_TITLE:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_LINKTITLE:

				return GD_TEMPLATE_FORMCOMPONENT_CUP_TITLE;	

			case GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_STATUS:

				return GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_LINK:

				return GD_TEMPLATE_FORMCOMPONENT_LINK;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_LINKACCESS:

				return GD_TEMPLATE_FORMCOMPONENT_LINKACCESS;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKACCESS:
			
				return GD_TEMPLATE_FILTERFORMCOMPONENT_LINKACCESS;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_LINKCATEGORIES:

				return GD_TEMPLATE_FORMCOMPONENT_LINKCATEGORIES;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO:

				return GD_TEMPLATE_FORMCOMPONENT_APPLIESTO;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES:

				return GD_TEMPLATE_FORMCOMPONENT_CATEGORIES;

			// case GD_TEMPLATE_FORMCOMPONENTGROUP_SECTIONS:

			// 	return GD_TEMPLATE_FORMCOMPONENT_SECTIONS;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_WEBPOSTSECTIONS:

				return GD_TEMPLATE_FORMCOMPONENT_WEBPOSTSECTIONS;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS:

				return GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_LINKCATEGORIES:
			
				return GD_TEMPLATE_FILTERFORMCOMPONENT_LINKCATEGORIES;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_APPLIESTO:
			
				return GD_TEMPLATE_FILTERFORMCOMPONENT_APPLIESTO;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_CATEGORIES:
			
				return GD_TEMPLATE_FILTERFORMCOMPONENT_CATEGORIES;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SECTIONS:
			
				return GD_TEMPLATE_FILTERFORMCOMPONENT_SECTIONS;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_WEBPOSTSECTIONS:
			
				return GD_TEMPLATE_FILTERFORMCOMPONENT_WEBPOSTSECTIONS;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_HIGHLIGHTEDITOR:

				return GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR;
			
			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_HIGHLIGHTEDPOST:

				return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_CATEGORIES:

				return GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_CATEGORIES;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_SECTIONS:

				return GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_SECTIONS;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_WEBPOSTSECTIONS:

				return GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS;

		}
		
		return parent::get_component($template_id);
	}

	function get_info($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_CUP_STATUS:

				return __('\'Draft\': still editing, our website admin will not publish it yet. \'Finished editing\': our website admin will publish it (almost) immediately. \'Already published\': our website admin has published it, post is already online.', 'poptheme-wassup');

			case GD_TEMPLATE_FORMCOMPONENTGROUP_LINK:
		
				return __('The URL from any webpage. (Not all websites can be embedded: Facebook, Dropbox and others do not permit it, and browsers do not embed websites on HTTP for security reasons.)', 'poptheme-wassup');

			case GD_TEMPLATE_FORMCOMPONENTGROUP_LINKTITLE:

				return __('Please copy/paste here the title from the original article.', 'poptheme-wassup');

			case GD_TEMPLATE_FORMCOMPONENTGROUP_HIGHLIGHTEDITOR:

				return __('Please copy/paste any important content from the original post.', 'poptheme-wassup');
		}
		
		return parent::get_info($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_LINK:

				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'placeholder', 'https://www.malaysiakini.com/...');
				break;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_HIGHLIGHTEDITOR:
				
				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'placeholder', __('Copy/paste here...', 'poptheme-wassup'));
				break;			

			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_HIGHLIGHTEDPOST:

				// Do not show the input, we don't need it since the related is only 1 and already pre-selected
				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'unique-preselected', true);
				// $this->append_att($component, $atts, 'input-class', 'hidden');
				// $this->add_att($component, $atts, 'max-selected', 1);

				// // Hide the close button
				// $this->add_att($component, $atts, 'show-close-btn', false);

				// Remove the `inline` property from all typeaheads selected elements
				$this->add_att($component, $atts, 'alert-class', 'alert-sm alert-warning fade');
				break;
				
			case GD_TEMPLATE_FORMCOMPONENTGROUP_LINKCATEGORIES:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_CATEGORIES:
			
				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'label', __('Select categories', 'poptheme-wassup'));
				break;

			// case GD_TEMPLATE_FORMCOMPONENTGROUP_SECTIONS:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_WEBPOSTSECTIONS:
			
				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'label', __('Select sections', 'poptheme-wassup'));
				break;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_APPLIESTO:
			
				// Allow to override by whoever is establishing the "applies to" values. Eg: "Select countries"
				$label = apply_filters(
					'GD_Template_Processor_CreateUpdatePostFormComponentGroups:appliesto:label',
					__('Applies to', 'poptheme-wassup')
				);
				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'label', $label);
				break;

		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_HIGHLIGHTEDPOST:

				// return __('Extract from:', 'poptheme-wassup');
				return __('Highlight from:', 'poptheme-wassup');

			case GD_TEMPLATE_FORMCOMPONENTGROUP_HIGHLIGHTEDITOR:

				// return __('Important information:', 'poptheme-wassup');
				return __('Highlight:', 'poptheme-wassup');
		}
		
		return parent::get_label($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateUpdatePostFormComponentGroups();