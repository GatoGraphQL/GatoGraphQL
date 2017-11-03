<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-previewpost-post-details'));
define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-previewpost-post-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST', PoP_TemplateIDUtils::get_template_definition('layout-automatedemails-previewpost-post-list'));

class PoPTheme_Wassup_AE_Template_Processor_PreviewPostLayouts extends GD_Template_Processor_CustomPreviewPostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST,
		);
	}	


	function get_author_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORNAME;
		}

		return parent::get_author_template($template_id);
	}

	function get_quicklinkgroup_bottom($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:

				return GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL;
		}

		return parent::get_quicklinkgroup_bottom($template_id);
	}

	function get_belowthumb_layouts($template_id) {

		$ret = parent::get_belowthumb_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:

				$ret[] = GD_TEMPLATE_LAYOUT_PUBLISHED;
				break;
		}

		return $ret;
	}

	function get_bottom_layouts($template_id) {

		$ret = parent::get_bottom_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:

				$ret[] = GD_TEMPLATE_QUICKLINKBUTTONGROUP_COMMENTS_LABEL;
				break;
		}

		return $ret;
	}

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:

				$ret[] = GD_TEMPLATE_LAYOUT_PUBLISHED;
				break;
		}

		return $ret;
	}

	function get_post_thumb_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL;

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:

				return GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM;
		}

		return parent::get_post_thumb_template($template_id);
	}

	function show_excerpt($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:

				return true;
		}

		return parent::show_excerpt($template_id);
	}

	function get_title_htmlmarkup($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
			
				return 'h3';
		}

		return parent::get_title_htmlmarkup($template_id, $atts);
	}

	function author_positions($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:

				return array(
					GD_CONSTANT_AUTHORPOSITION_ABOVETITLE,
				);
		}

		return parent::author_positions($template_id);
	}

	function horizontal_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
				
				return true;
		}

		return parent::horizontal_layout($template_id);
	}

	function horizontal_media_layout($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:

				return true;
		}

		return parent::horizontal_media_layout($template_id);
	}
	

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_LIST:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['excerpt'] = 'email-excerpt';
				$ret[GD_JS_CLASSES/*'classes'*/]['authors-abovetitle'] = 'email-authors-abovetitle';
				break;

			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_THUMBNAIL:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['belowthumb'] = 'bg-info text-info belowthumb';
				break;
		}

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_AUTOMATEDEMAILS_PREVIEWPOST_POST_DETAILS:
			
				$ret[GD_JS_CLASSES/*'classes'*/]['thumb'] = 'pop-thumb-framed';
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_AE_Template_Processor_PreviewPostLayouts();