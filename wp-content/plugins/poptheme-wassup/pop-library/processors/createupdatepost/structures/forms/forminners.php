<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_WEBPOSTLINK_UPDATE', PoP_TemplateIDUtils::get_template_definition('forminner-webpostlink-update'));
define ('GD_TEMPLATE_FORMINNER_WEBPOSTLINK_CREATE', PoP_TemplateIDUtils::get_template_definition('forminner-webpostlink-create'));
define ('GD_TEMPLATE_FORMINNER_HIGHLIGHT_UPDATE', PoP_TemplateIDUtils::get_template_definition('forminner-highlight-update'));
define ('GD_TEMPLATE_FORMINNER_HIGHLIGHT_CREATE', PoP_TemplateIDUtils::get_template_definition('forminner-highlight-create'));
define ('GD_TEMPLATE_FORMINNER_WEBPOST_UPDATE', PoP_TemplateIDUtils::get_template_definition('forminner-webpost-update'));
define ('GD_TEMPLATE_FORMINNER_WEBPOST_CREATE', PoP_TemplateIDUtils::get_template_definition('forminner-webpost-create'));

class Wassup_Template_Processor_CreateUpdatePostFormInners extends Wassup_Template_Processor_CreateUpdatePostFormInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMINNER_WEBPOSTLINK_UPDATE,
			GD_TEMPLATE_FORMINNER_WEBPOSTLINK_CREATE,
			GD_TEMPLATE_FORMINNER_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_FORMINNER_HIGHLIGHT_CREATE,
			GD_TEMPLATE_FORMINNER_WEBPOST_UPDATE,
			GD_TEMPLATE_FORMINNER_WEBPOST_CREATE,
		);
	}

	protected function is_link($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_FORMINNER_WEBPOSTLINK_UPDATE:

				return true;
		}

		return parent::is_link($template_id);
	}
	protected function is_update($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_UPDATE:
			case GD_TEMPLATE_FORMINNER_WEBPOST_UPDATE:

				return true;
		}

		return parent::is_update($template_id);
	}
	protected function get_featuredimage_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_UPDATE:

				return null;
		}

		return parent::get_featuredimage_input($template_id);
	}
	protected function get_coauthors_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_UPDATE:

				return null;
		}

		return parent::get_coauthors_input($template_id);
	}
	protected function get_title_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_UPDATE:

				return null;
		}

		return parent::get_title_input($template_id);
	}
	protected function get_editor_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_UPDATE:

				return GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR;
		}

		return parent::get_editor_input($template_id);
	}
	protected function get_status_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_CREATE:
			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_UPDATE:

				// Highlights are always published immediately, independently of value of GD_CONF_CREATEUPDATEPOST_MODERATE
				return GD_TEMPLATE_FORMCOMPONENT_CUP_KEEPASDRAFT;
		}

		return parent::get_status_input($template_id);
	}

	protected function get_sections_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_FORMINNER_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_FORMINNER_WEBPOST_CREATE:
			case GD_TEMPLATE_FORMINNER_WEBPOST_UPDATE:

				// if (PoPTheme_Wassup_Utils::add_webpost_sections()) {
				return GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS;
				// }
				// break;
		}

		return null;
	}

	function get_layouts($template_id) {

		// Comment Leo 03/04/2015: IMPORTANT!
		// For the _wpnonce and the pid, get the value from the iohandler when editing
		// Why? because otherwise, if first loading an Edit Discussion (eg: http://m3l.localhost/edit-discussion/?_wpnonce=e88efa07c5&pid=17887)
		// being the user logged out and only then he log in, the refetchBlock doesn't work because it doesn't have the pid/_wpnonce values
		// Adding it through GD_DATALOAD_IOHANDLER_EDITPOST allows us to have it there always, even if the post was not loaded since the user has no access to it
		$ret = parent::get_layouts($template_id);
		
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_FORMINNER_WEBPOSTLINK_CREATE:

				return array_merge(
					$ret,
					array(
						GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_LEFTSIDE,
						GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_RIGHTSIDE,
					)
				);

			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_UPDATE:
			case GD_TEMPLATE_FORMINNER_HIGHLIGHT_CREATE:

				return array_merge(
					$ret,
					array(
						GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_HIGHLIGHTEDPOST,
						GD_TEMPLATE_FORMCOMPONENTGROUP_HIGHLIGHTEDITOR,
						GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH,
					)
				);

			case GD_TEMPLATE_FORMINNER_WEBPOST_UPDATE:
			case GD_TEMPLATE_FORMINNER_WEBPOST_CREATE:

				return array_merge(
					$ret,
					array(
						GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_LEFTSIDE,
						GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_RIGHTSIDE,
					)
				);
		}

		return parent::get_components($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		if ($this->is_update($template_id)) {

			if ($sections = $this->get_sections_input($template_id)) {
				$this->add_att($sections, $atts, 'load-itemobject-value', true);
			}
		}

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_WEBPOSTLINK_CREATE:
			case GD_TEMPLATE_FORMINNER_WEBPOSTLINK_UPDATE:
			case GD_TEMPLATE_FORMINNER_WEBPOST_CREATE:
			case GD_TEMPLATE_FORMINNER_WEBPOST_UPDATE:

				$rightsides = array(
					GD_TEMPLATE_FORMINNER_WEBPOST_CREATE => GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_RIGHTSIDE,
					GD_TEMPLATE_FORMINNER_WEBPOST_UPDATE => GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_RIGHTSIDE,
					GD_TEMPLATE_FORMINNER_WEBPOSTLINK_CREATE => GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_RIGHTSIDE,
					GD_TEMPLATE_FORMINNER_WEBPOSTLINK_UPDATE => GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_RIGHTSIDE,
				);
				$leftside = $this->is_link($template_id) ? GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOSTLINK_LEFTSIDE : GD_TEMPLATE_MULTICOMPONENT_FORM_WEBPOST_LEFTSIDE;
				
				// Allow to override the classes, so it can be set for the Addons pageSection without the col-sm styles, but one on top of the other
				// if (!($form_row_classs = $this->get_general_att($atts, 'form-row-class'))) {
				// 	$form_row_classs = 'row';
				// }
				if (!($form_left_class = $this->get_general_att($atts, 'form-left-class'))) {
					$form_left_class = 'col-sm-8';
				}
				if (!($form_right_class = $this->get_general_att($atts, 'form-right-class'))) {
					$form_right_class = 'col-sm-4';
				}
				// $this->append_att($template_id, $atts, 'class', $form_row_classs);
				$this->append_att($leftside, $atts, 'class', $form_left_class);
				$this->append_att($rightsides[$template_id], $atts, 'class', $form_right_class);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_CreateUpdatePostFormInners();