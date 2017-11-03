<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS', PoP_TemplateIDUtils::get_template_definition('subcomponent-postcomments'));
define ('GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS', PoP_TemplateIDUtils::get_template_definition('lazysubcomponent-postcomments'));
// define ('GD_TEMPLATE_LAZYSUBCOMPONENT_MAXHEIGHTPOSTCOMMENTS', PoP_TemplateIDUtils::get_template_definition('lazysubcomponent-maxheightpostcomments'));
define ('GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS', PoP_TemplateIDUtils::get_template_definition('lazysubcomponent-noheaderpostcomments'));

class GD_Template_Processor_PostCommentSubcomponentLayouts extends GD_Template_Processor_SubcomponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS,
			GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS,
			// GD_TEMPLATE_LAZYSUBCOMPONENT_MAXHEIGHTPOSTCOMMENTS,
			GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS:

				$ret[] = GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT;
				break;

			case GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS:
			// case GD_TEMPLATE_LAZYSUBCOMPONENT_MAXHEIGHTPOSTCOMMENTS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:
				
				$ret[] = GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE;
				break;
		}

		return $ret;
	}

	function get_subcomponent_field($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS:

				return 'comments';

			case GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS:

				return 'comments-lazy';

			// case GD_TEMPLATE_LAZYSUBCOMPONENT_MAXHEIGHTPOSTCOMMENTS:

			// 	return 'comments-lazy|maxheight';

			case GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:

				return 'noheadercomments-lazy';
		}
	
		return parent::get_subcomponent_field($template_id);
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS:
			// case GD_TEMPLATE_LAZYSUBCOMPONENT_MAXHEIGHTPOSTCOMMENTS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:

				return GD_DATALOADER_COMMENTLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function is_individual($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS:
			// case GD_TEMPLATE_LAZYSUBCOMPONENT_MAXHEIGHTPOSTCOMMENTS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:

				return false;
		}
	
		return parent::is_individual($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS:
			// case GD_TEMPLATE_LAZYSUBCOMPONENT_MAXHEIGHTPOSTCOMMENTS:
			case GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS:

				$this->append_att($template_id, $atts, 'class', 'postcomments clearfix');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostCommentSubcomponentLayouts();