<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_CONTENT_POST', PoP_ServerUtils::get_template_definition('layout-content-post'));
define ('GD_TEMPLATE_LAYOUT_CONTENT_POSTCOMPACT', PoP_ServerUtils::get_template_definition('layout-content-postcompact'));
define ('GD_TEMPLATE_LAYOUT_CONTENT_COMMENT', PoP_ServerUtils::get_template_definition('layout-content-comment'));
define ('GD_TEMPLATE_LAYOUT_CONTENT_PAGE', PoP_ServerUtils::get_template_definition('layout-content-page'));

class GD_Template_Processor_ContentLayouts extends GD_Template_Processor_ContentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_CONTENT_POST,
			GD_TEMPLATE_LAYOUT_CONTENT_POSTCOMPACT,
			GD_TEMPLATE_LAYOUT_CONTENT_COMMENT,
			GD_TEMPLATE_LAYOUT_CONTENT_PAGE,
		);
	}	

	protected function get_usermentions_layout($template_id) {

		// Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_CONTENT_POST:
			case GD_TEMPLATE_LAYOUT_CONTENT_POSTCOMPACT:

				return GD_TEMPLATE_LAYOUT_POSTUSERMENTIONS;

			case GD_TEMPLATE_LAYOUT_CONTENT_COMMENT:

				return GD_TEMPLATE_LAYOUT_COMMENTUSERMENTIONS;
		}

		return null;
	}	

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		// Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_CONTENT_POST:
			case GD_TEMPLATE_LAYOUT_CONTENT_POSTCOMPACT:
			case GD_TEMPLATE_LAYOUT_CONTENT_COMMENT:

				$ret[] = $this->get_usermentions_layout($template_id);
				break;
		}

		return $ret;
	}

	function get_content_maxlength($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_CONTENT_POSTCOMPACT:

				// Length: 400 characters max
				return 400;
		}

		return parent::get_content_maxlength($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_CONTENT_POST:
			case GD_TEMPLATE_LAYOUT_CONTENT_POSTCOMPACT:
			case GD_TEMPLATE_LAYOUT_CONTENT_COMMENT:

				// Make the images inside img-responsive
				$this->add_jsmethod($ret, 'imageResponsive');
				
				// Add the popover for the @mentions
				$this->add_jsmethod($ret, 'contentPopover');
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		// Hide the @mentions popover code
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_CONTENT_POST:
			case GD_TEMPLATE_LAYOUT_CONTENT_POSTCOMPACT:
			case GD_TEMPLATE_LAYOUT_CONTENT_COMMENT:

				$usermentions = $this->get_usermentions_layout($template_id);
				$this->append_att($usermentions, $atts, 'class', 'hidden');
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_CONTENT_POST:
			// case GD_TEMPLATE_LAYOUT_CONTENT_POSTCOMPACT:

				$this->append_att($template_id, $atts, 'class', 'readable');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ContentLayouts();