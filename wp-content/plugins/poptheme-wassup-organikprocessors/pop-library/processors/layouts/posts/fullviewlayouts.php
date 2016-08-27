<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FULLVIEW_FARM', PoP_ServerUtils::get_template_definition('layout-fullview-farm'));
define ('GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_FARM', PoP_ServerUtils::get_template_definition('authorlayout-fullview-farm'));
define ('GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_FARM', PoP_ServerUtils::get_template_definition('singlelayout-fullview-farm'));

class OP_Template_Processor_CustomFullViewLayouts extends GD_Template_Processor_CustomFullViewLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FULLVIEW_FARM,
			GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_FARM,
			GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_FARM,
		);
	}


	
	function get_footer_templates($template_id) {

		$ret = parent::get_footer_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_FARM:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_FARM:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_FARM:

				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL;
				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION;
				$ret[] = GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_FULLVIEW;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_FARM:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_FARM:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_FARM:

				$this->merge_att(GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW, $atts, 'previoustemplates-ids', array(
					'data-lazyloadingspinner-target' => GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
				));
				// $this->add_att(GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY_APPENDABLE, $atts, 'show-lazyloading-spinner', true);
				break;
		}

		return parent::init_atts($template_id, $atts);
	}

	function get_sidebar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_FARM:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_FARM:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_FARM:

				$sidebars = array(
					GD_TEMPLATE_LAYOUT_FULLVIEW_FARM => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_FARM,
					GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_FARM => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_FARM,
					GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_FARM => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_FARM,
				);

				return $sidebars[$template_id];
		}

		return parent::get_sidebar_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomFullViewLayouts();