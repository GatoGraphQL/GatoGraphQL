<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CODE_DOMAINSTYLES', PoP_ServerUtils::get_template_definition('code-domainstyles'));

class GD_Template_Processor_DomainCodes extends GD_Template_Processor_CodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CODE_DOMAINSTYLES,
		);
	}

	function get_code($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CODE_DOMAINSTYLES:

				// Print all the inline styles for this domain
				$domain = GD_Template_Processor_DomainUtils::get_domain_from_request();
				$styles = 
					get_wassup_loggedin_domain_styles($domain). // Logged in the domain styles
					get_multidomain_bgcolor_style($domain); // Add the background color for the domain.
				if ($styles) {
					return sprintf(
						'<style type="text/css">%s</style>',
						$styles
					);
				}

				return '';
		}
	
		return parent::get_code($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DomainCodes();