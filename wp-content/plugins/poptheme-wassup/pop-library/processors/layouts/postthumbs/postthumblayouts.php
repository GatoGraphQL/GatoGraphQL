<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_FAVICON', PoP_ServerUtils::get_template_definition('layout-postthumb-favicon'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE', PoP_ServerUtils::get_template_definition('layout-postthumb-originalfeaturedimage'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE', PoP_ServerUtils::get_template_definition('layout-postthumb-featuredimage'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER', PoP_ServerUtils::get_template_definition('layout-postthumb-featuredimage-volunteer'));

define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_XSMALL', PoP_ServerUtils::get_template_definition('layout-postthumb-xsmall'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL', PoP_ServerUtils::get_template_definition('layout-postthumb-croppedsmall'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM', PoP_ServerUtils::get_template_definition('layout-postthumb-croppedmedium'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDFEED', PoP_ServerUtils::get_template_definition('layout-postthumb-croppedfeed'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT', PoP_ServerUtils::get_template_definition('layout-postthumb-croppedsmall-edit'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER', PoP_ServerUtils::get_template_definition('layout-postthumb-croppedsmall-volunteer'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER', PoP_ServerUtils::get_template_definition('layout-postthumb-croppedmedium-volunteer'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER', PoP_ServerUtils::get_template_definition('layout-postthumb-croppedfeed-volunteer'));

define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED', PoP_ServerUtils::get_template_definition('layout-postthumb-linkselfcroppedfeed'));
define ('GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER', PoP_ServerUtils::get_template_definition('layout-postthumb-linkselfcroppedfeed-volunteer'));

class GD_Custom_Template_Processor_PostThumbLayouts extends GD_Template_Processor_PostThumbLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_POSTTHUMB_FAVICON,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_XSMALL,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDFEED,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED,
			GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER,
		);
	}

	function get_thumb_name($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_FAVICON:

				return 'thumb-favicon';

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:

				return 'thumb-pagewide';//'thumb-large';

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_XSMALL:

				return 'thumb-xs';

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER:

				return 'thumb-sm';

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER:

				return 'thumb-md';

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDFEED:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:

				return 'thumb-feed';
		}

		return parent::get_thumb_name($template_id, $atts);
	}

	function get_url_field($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:

				return 'thumb-full-src';
			
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT:
				
				return 'edit-url';
		}

		return parent::get_url_field($template_id);
	}

	function get_linktarget($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_EDIT:

				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					
					return GD_URLPARAM_TARGET_ADDONS;
				}
				break;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	function get_extra_thumb_layouts($template_id) {

		$ret = parent::get_extra_thumb_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDSMALL_VOLUNTEER:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDMEDIUM_VOLUNTEER:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDFEED_VOLUNTEER:
			// case GD_TEMPLATE_LAYOUT_POSTTHUMB_CROPPEDLARGE_VOLUNTEER:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
				
				// Only if the Volunteering is enabled
				if (POPTHEME_WASSUP_GF_PAGE_VOLUNTEER) {
			
					$ret[] = GD_TEMPLATE_LAYOUT_POSTADDITIONAL_VOLUNTEER;
				}
				break;

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_XSMALL:

				// Override the parent since we don't want to show the multipost-labels here, this thumb is too small
				return array();
		}

		return $ret;
	}

	function get_thumb_link_class($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
			
				return 'thumbnail';
		}

		return parent::get_thumb_link_class($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_POSTTHUMB_ORIGINALFEATUREDIMAGE:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_FEATUREDIMAGE_VOLUNTEER:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED:
			case GD_TEMPLATE_LAYOUT_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER:
			
				$this->merge_att($template_id, $atts, 'itemobject-params', array(
					'data-size' => 'thumb-full-dimensions'
				));
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_PostThumbLayouts();