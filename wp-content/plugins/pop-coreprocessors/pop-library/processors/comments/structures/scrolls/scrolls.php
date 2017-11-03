<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCROLL_COMMENTS_LIST', PoP_TemplateIDUtils::get_template_definition('scroll-comments-list'));
define ('GD_TEMPLATE_SCROLL_COMMENTS_ADD', PoP_TemplateIDUtils::get_template_definition('scroll-comments-add'));
define ('GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT', PoP_TemplateIDUtils::get_template_definition('layout-postcomment-scroll'));
define ('GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE', PoP_TemplateIDUtils::get_template_definition('layout-postcomment-scroll-appendable'));
define ('GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_DETAILS', PoP_TemplateIDUtils::get_template_definition('layout-referencedby-scroll-details'));
define ('GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW', PoP_TemplateIDUtils::get_template_definition('layout-referencedby-scroll-simpleview'));
define ('GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('layout-referencedby-scroll-fullview'));
define ('GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE', PoP_TemplateIDUtils::get_template_definition('layout-referencedby-scroll-appendable'));

class GD_Template_Processor_CommentScrolls extends GD_Template_Processor_ScrollsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCROLL_COMMENTS_LIST,
			GD_TEMPLATE_SCROLL_COMMENTS_ADD,
			GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT,
			GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE,
			GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_DETAILS,
			GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW,
			GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW,
			GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE,
		);
	}

	function get_inner_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SCROLL_COMMENTS_LIST:

				return GD_TEMPLATE_SCROLLINNER_COMMENTS_LIST;

			case GD_TEMPLATE_SCROLL_COMMENTS_ADD:

				return GD_TEMPLATE_SCROLLINNER_COMMENTS_ADD;

			case GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT:

				return GD_TEMPLATE_LAYOUTSCROLLINNER_POSTCOMMENTS;

			case GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:

				return GD_TEMPLATE_LAYOUTSCROLLINNER_POSTCOMMENTS_APPENDABLE;
			
			case GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_DETAILS:
				
				return GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_DETAILS;
			
			case GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW:
				
				return GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_SIMPLEVIEW;
			
			case GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW:
				
				return GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_FULLVIEW;
			
			case GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:

				return GD_TEMPLATE_LAYOUTSCROLLINNER_REFERENCEDBY_APPENDABLE;
		}

		return parent::get_inner_template($template_id);
	}

	function add_fetched_data($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT:
			case GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
			case GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_DETAILS:
			case GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_SIMPLEVIEW:
			case GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_FULLVIEW:
			case GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:

				return false;
		}
	
		return parent::add_fetched_data($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SCROLL_COMMENTS_LIST:

				$vars = GD_TemplateManager_Utils::get_vars();
				$post = $vars['global-state']['post']/*global $post*/;
				$this->append_att($template_id, $atts, 'class', 'pop-commentpost-'.$post->ID);
				$this->append_att($template_id, $atts, 'class', 'pop-postcomment');
				break;
		
			case GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
			case GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE:

				$classes = array(
					GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE => 'comments',
					GD_TEMPLATE_SCROLLLAYOUT_REFERENCEDBY_APPENDABLE => 'references',
				);
		
				$this->add_att($template_id, $atts, 'appendable', true);
				$this->add_att($template_id, $atts, 'appendable-class', $classes[$template_id]);

				// Show the lazy loading spinner?
				// if ($this->get_att($template_id, $atts, 'show-lazyloading-spinner')) {

				// 	$this->add_att($template_id, $atts, 'description', GD_CONSTANT_LAZYLOAD_LOADINGDIV);
				// }
				break;
		}

		// switch ($template_id) {

		// 	case GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT:
		// 	case GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE:
		
		// 		$this->append_att($template_id, $atts, 'inner-class', 'pop-commentsinner');
		// 		break;
		// }

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentScrolls();