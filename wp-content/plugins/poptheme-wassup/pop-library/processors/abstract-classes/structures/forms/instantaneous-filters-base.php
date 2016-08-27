<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// class GD_Template_Processor_CategoriesDelegatorFiltersBase extends GD_Template_Processor_CustomDelegatorFiltersBase {
class GD_Template_Processor_InstantaneousFiltersBase extends GD_Template_Processor_FiltersBase {

	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', 'pop-filterform-instantaneous');
		
		// Add for the target for the onActionThenClick function
		if ($inner = $this->get_inner_template($template_id)) {
			$this->add_att($inner, $atts, 'trigger-template', $template_id);
		}
		return parent::init_atts($template_id, $atts);
	}
}
