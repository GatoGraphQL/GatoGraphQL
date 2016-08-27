<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CreateUpdatePostFormInnersBase extends GD_Template_Processor_FormInnersBase {

	protected function is_update($template_id) {

		return false;
	}

	protected function get_featuredimage_input($template_id) {

		return GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGE;
	}
	protected function get_references_input($template_id) {

		return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES;
	}
	protected function get_coauthors_input($template_id) {

		return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS;
	}
	protected function get_title_input($template_id) {

		return GD_TEMPLATE_FORMCOMPONENT_CUP_TITLE;
	}
	protected function get_editor_input($template_id) {

		return GD_TEMPLATE_FORMCOMPONENT_EDITOR;
	}
	protected function get_status_input($template_id) {

		if (!GD_CreateUpdate_Utils::moderate()) {

			return GD_TEMPLATE_FORMCOMPONENT_CUP_KEEPASDRAFT;
		}
		
		return GD_TEMPLATE_FORMCOMPONENT_CUP_STATUS;
	}
	protected function get_editor_initialvalue($template_id) {

		return null;
	}

	function init_atts($template_id, &$atts) {

		// Load the values from the singleItem into the formcomponent fields
		if ($this->is_update($template_id)) {

			if ($editor = $this->get_editor_input($template_id)) {
				$this->add_att($editor, $atts, 'load-itemobject-value', true);
			}
			if ($status = $this->get_status_input($template_id)) {
				$this->add_att($status, $atts, 'load-itemobject-value', true);
			}
			if ($title = $this->get_title_input($template_id)) {
				$this->add_att($title, $atts, 'load-itemobject-value', true);
			}
			if ($featuredimage = $this->get_featuredimage_input($template_id)) {
				$this->add_att($featuredimage, $atts, 'load-itemobject-value', true);
			}
			if ($references = $this->get_references_input($template_id)) {
				$this->add_att($references, $atts, 'load-itemobject-value', true);
			}
			if ($coauthors = $this->get_coauthors_input($template_id)) {
				$this->add_att($coauthors, $atts, 'load-itemobject-value', true);
			}
		}		
		else {

			// Set an initial value?
			if ($initialvalue = $this->get_editor_initialvalue($template_id)) {

				// $initialvalue = apply_filters('the_editor_content', $initialvalue);
				$editor = $this->get_editor_input($template_id);
				$this->add_att($editor, $atts, 'selected', $initialvalue);
			}

			// Allow it to initialize values, so we can execute this URL to pre-select the related posts: 
			// http://m3l.localhost/add-project/?related%5B0%5D=18287
			if ($references = $this->get_references_input($template_id)) {
				$this->add_att($references, $atts, 'initialize-value', true);
			}
		}
		
		return parent::init_atts($template_id, $atts);
	}
}
