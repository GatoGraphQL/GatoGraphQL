<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_CONTACTUS', PoP_TemplateIDUtils::get_template_definition('block-contactus'));
define ('GD_TEMPLATE_BLOCK_CONTACTUSER', PoP_TemplateIDUtils::get_template_definition('block-contactuser'));
define ('GD_TEMPLATE_BLOCK_SHAREBYEMAIL', PoP_TemplateIDUtils::get_template_definition('block-sharebyemail'));
define ('GD_TEMPLATE_BLOCK_VOLUNTEER', PoP_TemplateIDUtils::get_template_definition('block-volunteer'));
define ('GD_TEMPLATE_BLOCK_FLAG', PoP_TemplateIDUtils::get_template_definition('block-flag'));
define ('GD_TEMPLATE_BLOCK_NEWSLETTER', PoP_TemplateIDUtils::get_template_definition('block-newsletter'));
define ('GD_TEMPLATE_BLOCKCODE_NEWSLETTER', PoP_TemplateIDUtils::get_template_definition('blockcode-newsletter'));
define ('GD_TEMPLATE_BLOCK_NEWSLETTERUNSUBSCRIPTION', PoP_TemplateIDUtils::get_template_definition('block-newsletterunsubscription'));

class GenericForms_Template_Processor_Blocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_CONTACTUS,
			GD_TEMPLATE_BLOCK_CONTACTUSER,
			GD_TEMPLATE_BLOCK_SHAREBYEMAIL,
			GD_TEMPLATE_BLOCK_VOLUNTEER,
			GD_TEMPLATE_BLOCK_FLAG,
			GD_TEMPLATE_BLOCK_NEWSLETTER,
			GD_TEMPLATE_BLOCKCODE_NEWSLETTER,
			GD_TEMPLATE_BLOCK_NEWSLETTERUNSUBSCRIPTION,
		);
	}

	function get_title($template_id) {

		switch ($template_id) {
					
			// case GD_TEMPLATE_BLOCK_CONTACTUSER:
			// case GD_TEMPLATE_BLOCK_VOLUNTEER:
			case GD_TEMPLATE_BLOCK_SHAREBYEMAIL:
			case GD_TEMPLATE_BLOCK_NEWSLETTER:

				return '';
		}
		
		return parent::get_title($template_id);
	}

	protected function get_messagefeedback($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_CONTACTUS:

				return GD_TEMPLATE_MESSAGEFEEDBACK_CONTACTUS;

			case GD_TEMPLATE_BLOCK_CONTACTUSER:

				return GD_TEMPLATE_MESSAGEFEEDBACK_CONTACTUSER;

			case GD_TEMPLATE_BLOCK_SHAREBYEMAIL:

				return GD_TEMPLATE_MESSAGEFEEDBACK_SHAREBYEMAIL;

			case GD_TEMPLATE_BLOCK_VOLUNTEER:

				return GD_TEMPLATE_MESSAGEFEEDBACK_VOLUNTEER;

			case GD_TEMPLATE_BLOCK_FLAG:

				return GD_TEMPLATE_MESSAGEFEEDBACK_FLAG;

			case GD_TEMPLATE_BLOCK_NEWSLETTER:
			case GD_TEMPLATE_BLOCKCODE_NEWSLETTER:
		
				return GD_TEMPLATE_MESSAGEFEEDBACK_NEWSLETTER;

			case GD_TEMPLATE_BLOCK_NEWSLETTERUNSUBSCRIPTION:
		
				return GD_TEMPLATE_MESSAGEFEEDBACK_NEWSLETTERUNSUBSCRIPTION;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_CONTACTUS:

				$ret[] = GD_TEMPLATE_FORM_CONTACTUS;
				break;

			case GD_TEMPLATE_BLOCK_CONTACTUSER:

				$ret[] = GD_TEMPLATE_FORM_CONTACTUSER;
				break;

			case GD_TEMPLATE_BLOCK_SHAREBYEMAIL:

				$ret[] = GD_TEMPLATE_FORM_SHAREBYEMAIL;
				break;
				
			case GD_TEMPLATE_BLOCK_VOLUNTEER:

				$ret[] = GD_TEMPLATE_FORM_VOLUNTEER;
				break;
				
			case GD_TEMPLATE_BLOCK_FLAG:

				$ret[] = GD_TEMPLATE_FORM_FLAG;
				break;
				
			case GD_TEMPLATE_BLOCK_NEWSLETTER:

				$ret[] = GD_TEMPLATE_FORM_NEWSLETTER;
				// $ret[] = GD_TEMPLATE_PAGECODE_NEWSLETTER;
				break;

			case GD_TEMPLATE_BLOCKCODE_NEWSLETTER:

				$ret[] = GD_TEMPLATE_PAGECODE_NEWSLETTER;
				$ret[] = GD_TEMPLATE_FORM_NEWSLETTER;
				break;
				
			case GD_TEMPLATE_BLOCK_NEWSLETTERUNSUBSCRIPTION:

				$ret[] = GD_TEMPLATE_FORM_NEWSLETTERUNSUBSCRIPTION;
				break;
		}
	
		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_CONTACTUS:
			case GD_TEMPLATE_BLOCK_CONTACTUSER:
			case GD_TEMPLATE_BLOCK_SHAREBYEMAIL:
			case GD_TEMPLATE_BLOCK_VOLUNTEER:
			case GD_TEMPLATE_BLOCK_FLAG:
			case GD_TEMPLATE_BLOCK_NEWSLETTER:
			case GD_TEMPLATE_BLOCKCODE_NEWSLETTER:
			case GD_TEMPLATE_BLOCK_NEWSLETTERUNSUBSCRIPTION:
			
				// Change the 'Loading' message in the Status
				$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', __('Sending...', 'pop-genericforms'));	
				break;

		}

		$newsletter_description = apply_filters(
			'GD_Template_Processor_GFBlocks:newsletter:description', 
			__('Subscribe to our newsletter:', 'pop-genericforms')
		);
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NEWSLETTER:

				$title_tag = apply_filters(
					'GD_Template_Processor_GFBlocks:newsletter:titletag', 
					'h3'
				);
				$description = sprintf(
					'<%1$s>%2$s</%1$s>',
					$title_tag,
					$newsletter_description
				);
				$this->add_att(GD_TEMPLATE_FORM_NEWSLETTER, $atts, 'description', $description);
				$this->append_att(GD_TEMPLATE_FORM_NEWSLETTER, $atts, 'class', 'form-inline');	

				if ($description_bottom = apply_filters(
					'GD_Template_Processor_GFBlocks:newsletter:descriptionbottom',
					__('Take a peek at our regular newsletter campaigns', 'pop-genericforms')
				)) {
					$description_bottom = sprintf(
						'<p><em><a href="%s" class="text-info">%s</a></em></p>',
						get_permalink(POP_GENERICFORMS_PAGE_NEWSLETTER),
						$description_bottom
					);
					
					$this->add_att(GD_TEMPLATE_FORM_NEWSLETTER, $atts, 'description-bottom', $description_bottom);
				}
				break;

			case GD_TEMPLATE_BLOCKCODE_NEWSLETTER:

				$description = sprintf(
					'<p>%s</p>',
					$newsletter_description
				);
				$this->add_att(GD_TEMPLATE_FORM_NEWSLETTER, $atts, 'description', $description);
				$this->append_att(GD_TEMPLATE_FORM_NEWSLETTER, $atts, 'class', 'alert alert-info');	
				break;

		}

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_NEWSLETTER:
			case GD_TEMPLATE_BLOCKCODE_NEWSLETTER:

				$this->append_att($template_id, $atts, 'class', 'block-newsletter');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	protected function get_iohandler($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_CONTACTUS:
			case GD_TEMPLATE_BLOCK_CONTACTUSER:
			case GD_TEMPLATE_BLOCK_SHAREBYEMAIL:
			case GD_TEMPLATE_BLOCK_VOLUNTEER:
			case GD_TEMPLATE_BLOCK_FLAG:
			case GD_TEMPLATE_BLOCK_NEWSLETTER:
			case GD_TEMPLATE_BLOCKCODE_NEWSLETTER:
			case GD_TEMPLATE_BLOCK_NEWSLETTERUNSUBSCRIPTION:
		
				// Allow Gravity Forms to replace it
				return apply_filters(
					'GenericForms_Template_Processor_Blocks:iohandler',
					GD_DATALOAD_IOHANDLER_FORM,
					$template_id
				);
		}

		return parent::get_iohandler($template_id);
	}

	function get_settings_id($template_id) {
	
		switch ($template_id) {

			// These 2 blocks cannot be placed under the same pageSection
			case GD_TEMPLATE_BLOCKCODE_NEWSLETTER:

				return GD_TEMPLATE_BLOCK_NEWSLETTER;
		}

		return parent::get_settings_id($template_id);
	}

	protected function get_block_page($template_id) {

	// // 	global $gd_template_settingsmanager;
	// 	global $gd_template_processor_manager;
		
		switch ($template_id) {

			case GD_TEMPLATE_BLOCKCODE_NEWSLETTER:

				// // if ($page = $gd_template_settingsmanager->get_block_page(GD_TEMPLATE_BLOCK_NEWSLETTER)) {

				// // 	return $page;
				// // }
				// // break;
				// return $gd_template_processor_manager->get_processor(GD_TEMPLATE_BLOCK_NEWSLETTER)->get_block_page(GD_TEMPLATE_BLOCK_NEWSLETTER);
				return $this->get_block_page(GD_TEMPLATE_BLOCK_NEWSLETTER);
		}
	
		return parent::get_block_page($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GenericForms_Template_Processor_Blocks();
