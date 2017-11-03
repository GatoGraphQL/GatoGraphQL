<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLLINNER_COMMENTS_LIST', PoP_TemplateIDUtils::get_template_definition('scrollinner-comments-list'));
define ('GD_TEMPLATE_SCROLLINNER_COMMENTS_ADD', PoP_TemplateIDUtils::get_template_definition('scrollinner-comments-add'));
define ('GD_TEMPLATE_LAYOUTSCROLLINNER_POSTCOMMENTS', PoP_TemplateIDUtils::get_template_definition('layout-postcommentscroll-inner'));
define ('GD_TEMPLATE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE', PoP_TemplateIDUtils::get_template_definition('layout-postcommentscroll-inner-appendable'));
define ('GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-referencedbyscroll-inner-details'));
define ('GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('layout-referencedbyscroll-inner-simpleview'));
define ('GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('layout-referencedbyscroll-inner-fullview'));
define ('GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE', PoP_TemplateIDUtils::get_template_definition('layout-referencedbyscroll-inner-appendable'));

class GD_Template_Processor_CommentScrollInners extends GD_Template_Processor_ScrollInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLLINNER_COMMENTS_LIST,
			GD_TEMPLATE_SCROLLINNER_COMMENTS_ADD,
			GD_TEMPLATE_LAYOUTSCROLLINNER_POSTCOMMENTS,
			GD_TEMPLATE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE,
			GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS,
			GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW,
			GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE,
		);
	}

	function get_layout_grid($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SCROLLINNER_COMMENTS_LIST:
			case GD_TEMPLATE_SCROLLINNER_COMMENTS_ADD:
			case GD_TEMPLATE_LAYOUTSCROLLINNER_POSTCOMMENTS:
			case GD_TEMPLATE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE:
			case GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE:

				return array(
					'row-items' => 1, 
					'class' => 'col-sm-12'
				);
		}

		return parent::get_layout_grid($template_id, $atts);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SCROLLINNER_COMMENTS_LIST:
			case GD_TEMPLATE_LAYOUTSCROLLINNER_POSTCOMMENTS:

				$ret[] = GD_TEMPLATE_LAYOUT_COMMENTFRAME_LIST;
				break;

			case GD_TEMPLATE_SCROLLINNER_COMMENTS_ADD:

				$ret[] = GD_TEMPLATE_LAYOUT_COMMENTFRAME_ADD;
				break;

			case GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS:

				$ret[] = GD_TEMPLATE_LAYOUT_MULTIPLEPOST_RELATED; // GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LINE;
				break;

			case GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW:

				$ret[] = GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LINE;
				break;

			case GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW:

				$ret[] = GD_TEMPLATE_LAYOUT_MULTIPLEPOST_ADDONS;
				break;

			case GD_TEMPLATE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE:
			case GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE:

				// No need for anything, since this is the layout container, to be filled when the lazyload request comes back
				break;
		}

		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentScrollInners();