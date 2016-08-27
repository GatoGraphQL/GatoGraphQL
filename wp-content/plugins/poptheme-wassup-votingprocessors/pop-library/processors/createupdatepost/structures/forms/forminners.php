<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE', PoP_ServerUtils::get_template_definition('forminner-opinionatedvote-update'));
define ('GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('forminner-opinionatedvote-create'));

class VotingProcessors_Template_Processor_CreateUpdatePostFormInners extends Wassup_Template_Processor_CreateUpdatePostFormInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_CREATE,
		);
	}

	protected function is_update($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE:

				return true;
		}

		return parent::is_update($template_id);
	}
	protected function get_featuredimage_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE:

				return null;
		}

		return parent::get_featuredimage_input($template_id);
	}
	protected function get_coauthors_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE:

				return null;
		}

		return parent::get_coauthors_input($template_id);
	}
	protected function get_title_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE:

				return null;
		}

		return parent::get_title_input($template_id);
	}
	protected function get_editor_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE:

				return GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR;
		}

		return parent::get_editor_input($template_id);
	}
	protected function get_status_input($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE:

				// OpinionatedVoteds are always published immediately, independently of value of GD_CONF_CREATEUPDATEPOST_MODERATE
				return GD_TEMPLATE_FORMCOMPONENT_CUP_KEEPASDRAFT;
		}

		return parent::get_status_input($template_id);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
		
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE:
			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_CREATE:

				return array_merge(
					$ret,
					array(
						GD_TEMPLATE_MULTICOMPONENT_FORM_OPINIONATEDVOTE_MAYBELEFTSIDE,
						GD_TEMPLATE_MULTICOMPONENT_FORM_OPINIONATEDVOTE_MAYBERIGHTSIDE,
						// GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_OPINIONATEDVOTEEDPOST,
						// GD_TEMPLATE_FORMCOMPONENTGROUP_OPINIONATEDVOTEEDITOR,
						// GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_STANCE,
						// GD_TEMPLATE_MULTICOMPONENT_FORMCOMPONENTS_UNMODERATEDPUBLISH,
					)
				);
		}

		return parent::get_components($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE:

				// Load the values from the singleItem into the formcomponent fields
				if ($this->is_update($template_id)) {

					$this->add_att(GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_STANCE, $atts, 'load-itemobject-value', true);
				}		
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CreateUpdatePostFormInners();