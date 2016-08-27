<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_OPINIONATEDVOTEEDITOR', PoP_ServerUtils::get_template_definition('formcomponent-opinionatedvoteeditorgroup'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_OPINIONATEDVOTEEDPOST', PoP_ServerUtils::get_template_definition('formcomponentgroup-selectabletypeahead-opinionatedvoteedpost'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_STANCE', PoP_ServerUtils::get_template_definition('formcomponentgroup-buttongroup-stance'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_STANCE', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-buttongroup-stance'));

class VotingProcessors_Template_Processor_CreateUpdatePostFormComponentGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_OPINIONATEDVOTEEDITOR,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_OPINIONATEDVOTEEDPOST,
			GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_STANCE,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_STANCE,
		);
	}

	function get_component($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_OPINIONATEDVOTEEDITOR:

				return GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR;
			
			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_OPINIONATEDVOTEEDPOST:

				return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_STANCE:

				return GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_STANCE;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_STANCE:

				return GD_TEMPLATE_FILTERFORMCOMPONENT_STANCE_MULTISELECT;
		}
		
		return parent::get_component($template_id);
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_OPINIONATEDVOTEEDITOR:
				
				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'placeholder', __('Write here...', 'poptheme-wassup-votingprocessors'));
				break;	

			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_OPINIONATEDVOTEEDPOST:

				$this->append_att($template_id, $atts, 'class', 'pop-uniqueornone-selectabletypeahead-formgroup');

				// Do not show the input, we don't need it since the related is only 1 and already pre-selected
				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'unique-preselected', true);

				// Remove the `inline` property from all typeaheads selected elements
				$this->add_att($component, $atts, 'alert-class', 'alert-sm alert-warning fade');

				$trigger = $gd_template_processor_manager->get_processor($component)->get_trigger_template($component);
				$description = sprintf(
					'<em><label><strong>%s</strong></label></em>',
					__('After reading...', 'poptheme-wassup-votingprocessors')
				);
				$this->add_att($trigger, $atts, 'description', $description);
				break;

		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_info($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENTGROUP_OPINIONATEDVOTEEDITOR:

				return __('You can leave 1 general opinion on TPP, and 1 opinion on TPP for each article on the website. Your opinions can be edited any moment.', 'poptheme-wassup-votingprocessors');
		}
		
		return parent::get_info($template_id, $atts);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_OPINIONATEDVOTEEDPOST:

				return '';
				// return __('After reading...', 'poptheme-wassup-votingprocessors');

			case GD_TEMPLATE_FORMCOMPONENTGROUP_OPINIONATEDVOTEEDITOR:

				return PoPTheme_Wassup_VotingProcessors_Utils::get_whatisyourvote_title();
				// return __('What do you think about TPP?', 'poptheme-wassup-votingprocessors');

			case GD_TEMPLATE_FORMCOMPONENTGROUP_BUTTONGROUP_STANCE:

				return __('Your stance:', 'poptheme-wassup-votingprocessors');
		
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_STANCE:
		
				return __('Stance:', 'poptheme-wassup-votingprocessors');
		}
		
		return parent::get_label($template_id, $atts);
	}

	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_STANCE:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_BUTTONGROUP_STANCE:

				$ret .= ' col-sm-10';
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CreateUpdatePostFormComponentGroups();