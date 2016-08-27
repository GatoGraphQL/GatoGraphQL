<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENT_EMBEDPREVIEW', PoP_ServerUtils::get_template_definition('content-embedpreview'));
define ('GD_TEMPLATE_CONTENT_EMBED', PoP_ServerUtils::get_template_definition('content-embed'));
define ('GD_TEMPLATE_CONTENT_COPYSEARCHURL', PoP_ServerUtils::get_template_definition('content-copysearchurl'));

class GD_Template_Processor_ShareContents extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENT_EMBEDPREVIEW,
			GD_TEMPLATE_CONTENT_EMBED,
			GD_TEMPLATE_CONTENT_COPYSEARCHURL,
		);
	}

	protected function get_description($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_CONTENT_EMBEDPREVIEW:

				return sprintf(
					'<h4><i class="fa fa-fw fa-eye"></i>%s</h4>',
					__('Preview:', 'pop-coreprocessors')
				);

			case GD_TEMPLATE_CONTENT_EMBED:

				return sprintf(
					'<p><em>%s</em></p>',
					__('Please copy/paste the html code below into your website.', 'pop-coreprocessors')
				);

			case GD_TEMPLATE_CONTENT_COPYSEARCHURL:

				return sprintf(
					'<p><em>%s</em></p>',
					__('Please select and copy (Ctrl + C) the URL below.', 'pop-coreprocessors')
				);
		}

		return parent::get_description($template_id, $atts);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CONTENT_EMBEDPREVIEW:

				return GD_TEMPLATE_CONTENTINNER_EMBEDPREVIEW;

			case GD_TEMPLATE_CONTENT_EMBED:

				return GD_TEMPLATE_CONTENTINNER_EMBED;

			case GD_TEMPLATE_CONTENT_COPYSEARCHURL:

				return GD_TEMPLATE_CONTENTINNER_COPYSEARCHURL;
		}

		return parent::get_inner_template($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ShareContents();