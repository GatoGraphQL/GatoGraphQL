<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CODE_OURSPONSORSINTRO', PoP_TemplateIDUtils::get_template_definition('code-oursponsorsintro'));
define ('GD_TEMPLATE_CODE_HOMEWELCOME', PoP_TemplateIDUtils::get_template_definition('code-homewelcome'));
define ('GD_TEMPLATE_CODE_LAZYLOADINGSPINNER', PoP_TemplateIDUtils::get_template_definition('code-lazyloadingspinner'));
define ('GD_TEMPLATE_CODE_EMPTY', PoP_TemplateIDUtils::get_template_definition('code-empty'));
define ('GD_TEMPLATE_CODE_VOLUNTEERWITHUSINTRO', PoP_TemplateIDUtils::get_template_definition('code-volunteerwithusintro'));
define ('GD_TEMPLATE_CODE_TRENDINGTAGSDESCRIPTION', PoP_TemplateIDUtils::get_template_definition('code-trendingtagsdescription'));

class GD_Template_Processor_Codes extends GD_Template_Processor_CodesBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CODE_OURSPONSORSINTRO,
			GD_TEMPLATE_CODE_HOMEWELCOME,
			GD_TEMPLATE_CODE_LAZYLOADINGSPINNER,
			GD_TEMPLATE_CODE_EMPTY,
			GD_TEMPLATE_CODE_VOLUNTEERWITHUSINTRO,
			GD_TEMPLATE_CODE_TRENDINGTAGSDESCRIPTION,
		);
	}

	function get_code($template_id, $atts) {

		$question = '<h4>%s</h4>';
		$response = '<p>%s</p><hr/>';
		$response_last = '<p>%s</p>';
		$li = '<li>%s</li>';

		switch ($template_id) {

			case GD_TEMPLATE_CODE_HOMEWELCOME:

				// $code = sprintf(
				// 	'<h4>%s</h4>',
				// 	// Allow qTrans to add the language links
				// 	apply_filters(
				// 		'GD_Template_Processor_CustomBlockGroups:homewelcometitle',
				// 		PoPTheme_Wassup_Utils::get_welcome_title(),
				// 		$template_id
				// 	)
				// );
				$code = sprintf(
					'<h3 class="media-heading">%s</h3>',
					PoPTheme_Wassup_Utils::get_welcome_title(false)
				);
				// $code .= sprintf(
				// 	'<div class="welcome-description">%s</div>',
				// 	gd_get_website_description(false)
				// );
				$imgcode = '';
				if ($img = gd_images_welcome()) {

					$imgattr = gd_images_attributes();
					$imgcode = sprintf(
						'<img src="%s" class="img-responsive" '.$imgattr.'>',
						$img
					);
				}
				$code .= apply_filters('GD_Template_Processor_Codes:description:welcome_image', $imgcode);
				$code .= gd_get_website_description(false);
				// $code .= '<hr/>';

				if ($websitecontent = apply_filters('GD_Template_Processor_Codes:home_welcome:content', array())) {

					$pages = $websitecontent['pages'];
					$descriptions = $websitecontent['descriptions'];
					$code .= sprintf(
						'<h3>%s</h3>',
						__('Here you will find:', 'poptheme-wassup')
					);
					$counter = 0; 
					$total = count($pages);
					$code .= '<div class="row">';
					foreach ($pages as $page => $createpage) {

						$url = get_permalink($page);
						$title = get_the_title($page);
						$description = $descriptions[$page];
						$item = sprintf(
							'<h4>%s<a href="%s">%s</a></h4><p>%s</p>',
							$createpage ? '<a href="'.get_permalink($createpage).'" class="pull-right"><i class="fa fa-fw fa-plus"></i></a>' : '',
							$url,
							$title,
							$description
						);
						$code .= sprintf(
							'<div class="col-sm-4">%s</div>',
							$item
						);

						$counter += 1;
						if ($counter % 3 == 0 && $counter != $total) {
							$code .= '</div><div class="row">';
						}
					}
					$code .= '</div>';//'<hr/>';
				}
				return $code;

			case GD_TEMPLATE_CODE_OURSPONSORSINTRO:

				return sprintf(
					'<div class="bg-info text-info">%s %s</div>', 
					apply_filters(
						'GD_Template_Processor_Codes:get_code:message',
						__('Many thanks to our sponsors and supporters.', 'poptheme-wassup'),
						$template_id
					),
					sprintf(
						__('If you or your organization wishes to participate, please visit <a href="%s">%s</a>.', 'poptheme-wassup'),
						get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS),
						get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS)
					)
				);

				// return sprintf(
				// 	'<div class="bg-info text-info">%s</div>', 
				// 	sprintf(
				// 		__('Many thanks to our sponsors and supporters. If you or your organization wishes to participate, please visit <a href="%s">%s</a>.', 'poptheme-wassup'),
				// 		get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS),
				// 		get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_SPONSORUS)
				// 	)
				// );

			case GD_TEMPLATE_CODE_LAZYLOADINGSPINNER:

				// return GD_CONSTANT_LAZYLOAD_LOADINGDIV;
				return sprintf(
					'<div class="pop-lazyload-loading">%s</div>',
					GD_CONSTANT_LOADING_SPINNER.' '.__('Loading data', 'poptheme-wassup')
				);

			case GD_TEMPLATE_CODE_VOLUNTEERWITHUSINTRO:

				return sprintf(
					'<div class="bg-info text-info">%s %s</div>', 
					apply_filters(
						'GD_Template_Processor_Codes:get_code:message',
						__('Many thanks to our volunteers.', 'poptheme-wassup'),
						$template_id
					),
					sprintf(
						__('If you\'d like to join us, please visit <a href="%s">%s</a>.', 'poptheme-wassup'),
						get_permalink(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS),
						get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ABOUT_VOLUNTEERWITHUS)
					)
				);

			case GD_TEMPLATE_CODE_TRENDINGTAGSDESCRIPTION:

				return sprintf(
					'<div class="bg-warning text-warning">%s</div>', 
					apply_filters(
						'GD_Template_Processor_Codes:get_code:message',
						sprintf(
							__('<strong>#Trending tags are:</strong><br/>Those tags which appear in the highest number of posts, during the last %s days.', 'poptheme-wassup'),
							POP_WPAPI_DAYS_TRENDINGTAGS
						),
						$template_id
					)
				);
		}
	
		return parent::get_code($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_Codes();