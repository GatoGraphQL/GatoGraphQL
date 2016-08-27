<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FULLVIEW_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('layout-fullview-opinionatedvote'));
define ('GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('authorlayout-fullview-opinionatedvote'));
define ('GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_OPINIONATEDVOTE', PoP_ServerUtils::get_template_definition('singlelayout-fullview-opinionatedvote'));

class VotingProcessors_Template_Processor_CustomFullViewLayouts extends GD_Template_Processor_CustomFullViewLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FULLVIEW_OPINIONATEDVOTE,
			GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_OPINIONATEDVOTE,
			GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_OPINIONATEDVOTE,
		);
	}

	function get_footer_templates($template_id) {

		$ret = parent::get_footer_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_OPINIONATEDVOTE:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_OPINIONATEDVOTE:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_OPINIONATEDVOTE:

				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL;
				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_USEROPINIONATEDVOTEPOSTINTERACTION;
				$ret[] = GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS;
				break;
		}

		return $ret;
	}

	function get_sidebar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_OPINIONATEDVOTE:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_OPINIONATEDVOTE:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_OPINIONATEDVOTE:

				$sidebars = array(
					GD_TEMPLATE_LAYOUT_FULLVIEW_OPINIONATEDVOTE => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_OPINIONATEDVOTE,
					GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_OPINIONATEDVOTE => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_OPINIONATEDVOTE,
					GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_OPINIONATEDVOTE => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_OPINIONATEDVOTE,
				);

				return $sidebars[$template_id];
		}

		return parent::get_sidebar_template($template_id);
	}

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_OPINIONATEDVOTE:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_OPINIONATEDVOTE:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_OPINIONATEDVOTE:

				$ret[GD_JS_CLASSES/*'classes'*/]['content'] = 'alert alert-opinionatedvote';
				$ret[GD_JS_CLASSES/*'classes'*/]['content-inner'] = 'readable';
				break;
		}
		
		return $ret;
	}

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_OPINIONATEDVOTE:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_OPINIONATEDVOTE:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_OPINIONATEDVOTE:

				$ret[] = GD_TEMPLATE_LAYOUTSTANCE;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_OPINIONATEDVOTE:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_OPINIONATEDVOTE:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_OPINIONATEDVOTE:

				$this->merge_att(GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS, $atts, 'previoustemplates-ids', array(
					'data-lazyloadingspinner-target' => GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
				));
				// $this->add_att(GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE, $atts, 'show-lazyloading-spinner', true);
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomFullViewLayouts();