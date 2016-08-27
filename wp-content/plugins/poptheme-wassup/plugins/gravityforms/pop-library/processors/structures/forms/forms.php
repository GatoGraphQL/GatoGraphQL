<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_CONTACTUS', PoP_ServerUtils::get_template_definition('form-contactus'));
define ('GD_TEMPLATE_FORM_CONTACTUSER', PoP_ServerUtils::get_template_definition('form-contactuser'));
define ('GD_TEMPLATE_FORM_SHAREBYEMAIL', PoP_ServerUtils::get_template_definition('form-sharebyemail'));
define ('GD_TEMPLATE_FORM_VOLUNTEER', PoP_ServerUtils::get_template_definition('form-volunteer'));
define ('GD_TEMPLATE_FORM_FLAG', PoP_ServerUtils::get_template_definition('form-flag'));
define ('GD_TEMPLATE_FORM_NEWSLETTER', PoP_ServerUtils::get_template_definition('form-newsletter'));

class GD_Template_Processor_GFForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORM_CONTACTUS,
			GD_TEMPLATE_FORM_CONTACTUSER,
			GD_TEMPLATE_FORM_SHAREBYEMAIL,
			GD_TEMPLATE_FORM_VOLUNTEER,
			GD_TEMPLATE_FORM_FLAG,
			GD_TEMPLATE_FORM_NEWSLETTER,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_FORM_CONTACTUS => GD_TEMPLATE_FORMINNER_CONTACTUS,
			GD_TEMPLATE_FORM_CONTACTUSER => GD_TEMPLATE_FORMINNER_CONTACTUSER,
			GD_TEMPLATE_FORM_SHAREBYEMAIL => GD_TEMPLATE_FORMINNER_SHAREBYEMAIL,
			GD_TEMPLATE_FORM_VOLUNTEER => GD_TEMPLATE_FORMINNER_VOLUNTEER,
			GD_TEMPLATE_FORM_FLAG => GD_TEMPLATE_FORMINNER_FLAG,
			GD_TEMPLATE_FORM_NEWSLETTER => GD_TEMPLATE_FORMINNER_NEWSLETTER,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_CONTACTUS:

				$email = apply_filters(
					'GD_Template_Processor_GFForms:contactus:email',
					gd_email_info_email()
				);

				// Add the description. Allow Organik Fundraising to override the message
				$description = sprintf(
					'<p><em>%s</em></p>',
					apply_filters(
						'GD_Template_Processor_GFForms:contactus:description',
						sprintf(
							__('Please write an email to <a href="mailto:%1$s">%1$s</a>, or fill in the form below:', 'poptheme-wassup'),
							$email
						),
						$email
					)
				);
				$this->add_att($template_id, $atts, 'description', $description);
				break;

			case GD_TEMPLATE_FORM_VOLUNTEER:

				// Add the description
				$description = sprintf(
					'<p><em>%s</em></p>',
					__('We will send the info below to the organizers, who should then get in touch with you.', 'poptheme-wassup')
				);
				$this->add_att($template_id, $atts, 'description', $description);
				break;

			case GD_TEMPLATE_FORM_FLAG:

				// Add the description
				$description = sprintf(
					'<p><em>%s</em></p>',
					__('Based on our users\' feedback, we will consider removing this post. You will receive a confirmation by email.', 'poptheme-wassup')
				);
				$this->add_att($template_id, $atts, 'description', $description);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_GFForms();