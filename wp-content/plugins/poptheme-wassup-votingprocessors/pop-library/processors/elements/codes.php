<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CODE_REFERENCEDAFTERREADING', PoP_ServerUtils::get_template_definition('code-referencedafterreading'));
define ('GD_TEMPLATE_CODE_AUTHORREFERENCEDAFTERREADING', PoP_ServerUtils::get_template_definition('code-authorreferencedafterreading'));
define ('GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_GENERAL', PoP_ServerUtils::get_template_definition('code-opinionatedvotecount-general'));
define ('GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_ARTICLE', PoP_ServerUtils::get_template_definition('code-opinionatedvotecount-article'));
define ('GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT', PoP_ServerUtils::get_template_definition('code-opinionatedvotecount'));
define ('GD_TEMPLATE_CODE_POSTSTANCE', PoP_ServerUtils::get_template_definition('code-poststance'));

class VotingProcessors_Custom_Template_Processor_Codes extends GD_Template_Processor_CodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CODE_REFERENCEDAFTERREADING,
			GD_TEMPLATE_CODE_AUTHORREFERENCEDAFTERREADING,
			GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_GENERAL,
			GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_ARTICLE,
			GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT,
			GD_TEMPLATE_CODE_POSTSTANCE,
		);
	}

	function get_code($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CODE_REFERENCEDAFTERREADING:

				return __('After reading', 'poptheme-wassup-votingprocessors');

			case GD_TEMPLATE_CODE_AUTHORREFERENCEDAFTERREADING:

				return sprintf(
					'<span class="pop-pulltextleft">%s</span>',
					__(', after reading', 'poptheme-wassup-votingprocessors')
				);

			case GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_GENERAL:

				return sprintf(
					'<strong>%s</strong>',
					__('Stances on TPP: ', 'poptheme-wassup-votingprocessors')
				);

			case GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_ARTICLE:

				return sprintf(
					'<strong>%s</strong>',
					__('Article-related stances on TPP: ', 'poptheme-wassup-votingprocessors')
				);

			case GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT:

				return sprintf(
					'<strong>%s</strong>',
					__('Combined: ', 'poptheme-wassup-votingprocessors')
				);

			case GD_TEMPLATE_CODE_POSTSTANCE:

				return sprintf(
					'<em>%s</em>',
					__('Stance from our users: ', 'poptheme-wassup-votingprocessors')
				);
		}
	
		return parent::get_code($template_id, $atts);
	}

	function get_html_tag($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CODE_REFERENCEDAFTERREADING:
			case GD_TEMPLATE_CODE_AUTHORREFERENCEDAFTERREADING:
			// case GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_GENERAL:
			// case GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_ARTICLE:
			// case GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT:
			case GD_TEMPLATE_CODE_POSTSTANCE:
				
				return 'span';
		}
	
		return parent::get_html_tag($template_id, $atts);
	}
	
	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;
	
		switch ($template_id) {

			// case GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_GENERAL:
			// case GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT_ARTICLE:
			// case GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT:
			case GD_TEMPLATE_CODE_POSTSTANCE:

				$this->append_att($template_id, $atts, 'class', 'btn btn-span');	
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_CODE_OPINIONATEDVOTECOUNT:

				$this->append_att($template_id, $atts, 'class', 'pop-opinionatedvote-combined');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Custom_Template_Processor_Codes();