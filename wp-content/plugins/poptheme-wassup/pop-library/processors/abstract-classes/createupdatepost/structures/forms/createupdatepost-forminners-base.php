<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class Wassup_Template_Processor_CreateUpdatePostFormInnersBase extends GD_Template_Processor_CreateUpdatePostFormInnersBase {

	protected function volunteering($template_id) {

		return false;
	}

	protected function has_locations($template_id) {

		return false;
	}

	protected function is_link($template_id) {

		return false;
	}

	protected function get_categories_input($template_id) {

		return GD_TEMPLATE_FORMCOMPONENT_CATEGORIES;
	}

	protected function get_appliesto_input($template_id) {

		return GD_TEMPLATE_FORMCOMPONENT_APPLIESTO;
	}
	
	function init_atts($template_id, &$atts) {

		// Load the values from the singleItem into the formcomponent fields
		if ($this->is_update($template_id)) {

			if ($categories = $this->get_categories_input($template_id)) {
				$this->add_att($categories, $atts, 'load-itemobject-value', true);
			}

			if ($appliesto = $this->get_appliesto_input($template_id)) {
				$this->add_att($appliesto, $atts, 'load-itemobject-value', true);
			}
			
			if ($this->has_locations($template_id)) {
				$this->add_att(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts, 'load-itemobject-value', true);						
			}

			if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER && $this->volunteering($template_id)) {
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_SELECT, $atts, 'load-itemobject-value', true);
			}

			if ($this->is_link($template_id)) {
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_LINK, $atts, 'load-itemobject-value', true);
		
				if (PoPTheme_Wassup_Utils::add_link_accesstype()) {

					$this->add_att(GD_TEMPLATE_FORMCOMPONENT_LINKACCESS, $atts, 'load-itemobject-value', true);
					// $this->add_att(GD_TEMPLATE_FORMCOMPONENT_LINKCATEGORIES, $atts, 'load-itemobject-value', true);
				}
			}
		}
		
		return parent::init_atts($template_id, $atts);
	}
}
