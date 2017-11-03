<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('GD_CUSTOM_TEMPLATE_CODE_OURSPONSORSINTRO', 'custom-code-oursponsorsintro');
// define ('GD_CUSTOM_TEMPLATE_CODE_VOLUNTEERWITHUSINTRO', 'custom-code-volunteerwithusintro');
define ('GD_TEMPLATE_CODE_ADVISEUSINTRO', PoP_TemplateIDUtils::get_template_definition('code-adviseusintro'));
define ('GD_TEMPLATE_CODE_DISCUSSIONDISCLAIMER', PoP_TemplateIDUtils::get_template_definition('code-discussiondisclaimer'));

class GD_Custom_Template_Processor_Codes extends GD_Template_Processor_CodesBase {

	function get_templates_to_process() {
	
		return array(
			// GD_CUSTOM_TEMPLATE_CODE_OURSPONSORSINTRO,
			// GD_CUSTOM_TEMPLATE_CODE_VOLUNTEERWITHUSINTRO,
			GD_TEMPLATE_CODE_ADVISEUSINTRO,
			GD_TEMPLATE_CODE_DISCUSSIONDISCLAIMER,
		);
	}

	function get_code($template_id, $atts) {

		switch ($template_id) {

			// case GD_CUSTOM_TEMPLATE_CODE_OURSPONSORSINTRO:

			// 	return sprintf(
			// 		'<div class="bg-info text-info">%s %s</div>', 
			// 		apply_filters(
			// 			'GD_Custom_Template_Processor_Codes:get_code:message',
			// 			__('Many thanks to our sponsors and supporters.', 'poptheme-wassup-sectionprocessors'),
			// 			$template_id
			// 		),
			// 		sprintf(
			// 			__('If you or your organization wishes to participate, please visit <a href="%s">%s</a>.', 'poptheme-wassup-sectionprocessors'),
			// 			get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS),
			// 			get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS)
			// 		)
			// 	);

			// case GD_CUSTOM_TEMPLATE_CODE_VOLUNTEERWITHUSINTRO:

			// 	return sprintf(
			// 		'<div class="bg-info text-info">%s %s</div>', 
			// 		apply_filters(
			// 			'GD_Custom_Template_Processor_Codes:get_code:message',
			// 			__('Many thanks to our volunteers.', 'poptheme-wassup-sectionprocessors'),
			// 			$template_id
			// 		),
			// 		sprintf(
			// 			__('If you\'d like to join us, please visit <a href="%s">%s</a>.', 'poptheme-wassup-sectionprocessors'),
			// 			get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS),
			// 			get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS)
			// 		)
			// 	);

			case GD_TEMPLATE_CODE_ADVISEUSINTRO:

				return sprintf(
					'<div class="bg-info text-info">%s</div>', 
					sprintf(
						__('We are constantly on the lookout for worthy, knowledgeable and experienced people who sympathize with our cause and want to empower us. If you are such a person, please <a href="%s">%s</a>.', 'poptheme-wassup-sectionprocessors'),
						get_permalink(POPTHEME_WASSUP_GF_PAGE_CONTACTUS),
						get_the_title(POPTHEME_WASSUP_GF_PAGE_CONTACTUS)
					)
				);

			case GD_TEMPLATE_CODE_DISCUSSIONDISCLAIMER:

				return sprintf(
					'<div class="discussions-disclaimer alert alert-warning"><small><strong>%s</strong> %s</small></div>',
					__('Disclaimer:', 'poptheme-wassup-sectionprocessors'),
					sprintf(
						__('The views expressed in this article/discussion do not necessarily represent the views of %1$s. Comments or opinions expressed are those of their respective contributors only. %1$s is not responsible for, and disclaims any and all liability for the content of the article/discussion and related comments.', 'poptheme-wassup-sectionprocessors'),
						get_bloginfo('name')
					)
				);
		}
	
		return parent::get_code($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_Codes();