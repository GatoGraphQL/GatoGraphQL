<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_OPINIONATEDVOTE_UPDATE', PoP_ServerUtils::get_template_definition('form-opinionatedvote-update'));
define ('GD_TEMPLATE_FORM_OPINIONATEDVOTE_CREATE', PoP_ServerUtils::get_template_definition('form-opinionatedvote-create'));

class VotingProcessors_Template_Processor_CreateUpdatePostForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORM_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_FORM_OPINIONATEDVOTE_CREATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FORM_OPINIONATEDVOTE_UPDATE => GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_UPDATE,
			GD_TEMPLATE_FORM_OPINIONATEDVOTE_CREATE => GD_TEMPLATE_FORMINNER_OPINIONATEDVOTE_CREATE,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORM_OPINIONATEDVOTE_CREATE:
			case GD_TEMPLATE_FORM_OPINIONATEDVOTE_UPDATE:

				// Make it horizontal? If set by above (most likely the block)
				if ($this->get_att($template_id, $atts, 'horizontal')) {
					$this->append_att($template_id, $atts, 'class', 'row');
					$this->append_att(GD_TEMPLATE_MULTICOMPONENT_FORM_OPINIONATEDVOTE_MAYBELEFTSIDE, $atts, 'class', 'col-sm-8');
					$this->append_att(GD_TEMPLATE_MULTICOMPONENT_FORM_OPINIONATEDVOTE_MAYBERIGHTSIDE, $atts, 'class', 'col-sm-4');
				}			
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CreateUpdatePostForms();