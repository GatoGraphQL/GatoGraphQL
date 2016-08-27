<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_FARM_UPDATE', PoP_ServerUtils::get_template_definition('forminner-farm-update'));
define ('GD_TEMPLATE_FORMINNER_FARMLINK_UPDATE', PoP_ServerUtils::get_template_definition('forminner-farmlink-update'));
define ('GD_TEMPLATE_FORMINNER_FARM_CREATE', PoP_ServerUtils::get_template_definition('forminner-farm-create'));
define ('GD_TEMPLATE_FORMINNER_FARMLINK_CREATE', PoP_ServerUtils::get_template_definition('forminner-farmlink-create'));

class OP_Template_Processor_CreateUpdatePostFormInners extends Wassup_Template_Processor_CreateUpdatePostFormInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMINNER_FARM_UPDATE,
			GD_TEMPLATE_FORMINNER_FARMLINK_UPDATE,
			GD_TEMPLATE_FORMINNER_FARM_CREATE,
			GD_TEMPLATE_FORMINNER_FARMLINK_CREATE,
		);
	}

	protected function is_link($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_FARMLINK_UPDATE:			
			case GD_TEMPLATE_FORMINNER_FARMLINK_CREATE:			

				return true;
		}

		return parent::is_link($template_id);
	}
	protected function volunteering($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_FARM_CREATE:
			case GD_TEMPLATE_FORMINNER_FARMLINK_CREATE:
			case GD_TEMPLATE_FORMINNER_FARM_UPDATE:
			case GD_TEMPLATE_FORMINNER_FARMLINK_UPDATE:

				return true;
		}

		return parent::volunteering($template_id);
	}
	protected function has_locations($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_FARM_CREATE:
			case GD_TEMPLATE_FORMINNER_FARMLINK_CREATE:
			case GD_TEMPLATE_FORMINNER_FARM_UPDATE:
			case GD_TEMPLATE_FORMINNER_FARMLINK_UPDATE:

				return true;
		}

		return parent::has_locations($template_id);
	}
	protected function is_update($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_FARM_UPDATE:
			case GD_TEMPLATE_FORMINNER_FARMLINK_UPDATE:

				return true;
		}

		return parent::is_update($template_id);
	}

	function get_layouts($template_id) {

		// Comment Leo 03/04/2015: IMPORTANT!
		// For the _wpnonce and the pid, get the value from the iohandler when editing
		// Why? because otherwise, if first loading an Edit Discussion (eg: http://m3l.localhost/edit-discussion/?_wpnonce=e88efa07c5&pid=17887)
		// being the user logged out and only then he log in, the refetchBlock doesn't work because it doesn't have the pid/_wpnonce values
		// Adding it through GD_DATALOAD_IOHANDLER_EDITPOST allows us to have it there always, even if the post was not loaded since the user has no access to it
		$ret = parent::get_layouts($template_id);
		
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_FARM_UPDATE:
			case GD_TEMPLATE_FORMINNER_FARM_CREATE:

				return array_merge(
					$ret,
					array(
						GD_TEMPLATE_MULTICOMPONENT_FORM_LEFTSIDE,
						GD_TEMPLATE_MULTICOMPONENT_FORM_FARM_RIGHTSIDE,
					)
				);

			case GD_TEMPLATE_FORMINNER_FARMLINK_UPDATE:
			case GD_TEMPLATE_FORMINNER_FARMLINK_CREATE:

				return array_merge(
					$ret,
					array(
						GD_TEMPLATE_MULTICOMPONENT_FORM_LINK_LEFTSIDE,
						GD_TEMPLATE_MULTICOMPONENT_FORM_FARMLINK_RIGHTSIDE,
					)
				);
		}

		return parent::get_components($template_id, $atts);
	}

	protected function get_editor_initialvalue($template_id) {

		// Allow RIPESS Asia to set an initial value for the Add Farm form
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_FARM_CREATE:
				
				return apply_filters('OP_Template_Processor_CreateUpdatePostFormInners:editor_initialvalue', null, $template_id);
		}

		return parent::get_editor_initialvalue($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_FARM_CREATE:
			case GD_TEMPLATE_FORMINNER_FARMLINK_CREATE:
			case GD_TEMPLATE_FORMINNER_FARM_UPDATE:
			case GD_TEMPLATE_FORMINNER_FARMLINK_UPDATE:

				// Make it into left/right columns
				$rightsides = array(
					GD_TEMPLATE_FORMINNER_FARM_CREATE => GD_TEMPLATE_MULTICOMPONENT_FORM_FARM_RIGHTSIDE,
					GD_TEMPLATE_FORMINNER_FARMLINK_CREATE => GD_TEMPLATE_MULTICOMPONENT_FORM_FARMLINK_RIGHTSIDE,
					GD_TEMPLATE_FORMINNER_FARM_UPDATE => GD_TEMPLATE_MULTICOMPONENT_FORM_FARM_RIGHTSIDE,
					GD_TEMPLATE_FORMINNER_FARMLINK_UPDATE => GD_TEMPLATE_MULTICOMPONENT_FORM_FARMLINK_RIGHTSIDE,
				);

				$leftside = $this->is_link($template_id) ? GD_TEMPLATE_MULTICOMPONENT_FORM_LINK_LEFTSIDE : GD_TEMPLATE_MULTICOMPONENT_FORM_LEFTSIDE;
				
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
new OP_Template_Processor_CreateUpdatePostFormInners();