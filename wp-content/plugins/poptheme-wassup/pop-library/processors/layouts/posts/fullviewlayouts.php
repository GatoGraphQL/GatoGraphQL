<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('layout-fullview'));
define ('GD_TEMPLATE_LAYOUT_FULLVIEW_LINK', PoP_TemplateIDUtils::get_template_definition('layout-fullview-link'));
define ('GD_TEMPLATE_LAYOUT_FULLVIEW_HIGHLIGHT', PoP_TemplateIDUtils::get_template_definition('layout-fullview-highlight'));
define ('GD_TEMPLATE_LAYOUT_FULLVIEW_WEBPOST', PoP_TemplateIDUtils::get_template_definition('layout-fullview-webpost'));

define ('GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('authorlayout-fullview'));
define ('GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_LINK', PoP_TemplateIDUtils::get_template_definition('authorlayout-fullview-link'));
define ('GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT', PoP_TemplateIDUtils::get_template_definition('authorlayout-fullview-highlight'));
define ('GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_WEBPOST', PoP_TemplateIDUtils::get_template_definition('authorlayout-fullview-webpost'));

define ('GD_TEMPLATE_SINGLELAYOUT_FULLVIEW', PoP_TemplateIDUtils::get_template_definition('singlelayout-fullview'));
define ('GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_LINK', PoP_TemplateIDUtils::get_template_definition('singlelayout-fullview-link'));
define ('GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT', PoP_TemplateIDUtils::get_template_definition('singlelayout-fullview-highlight'));
define ('GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_WEBPOST', PoP_TemplateIDUtils::get_template_definition('singlelayout-fullview-webpost'));

class GD_Template_Processor_CustomFullViewLayouts extends GD_Template_Processor_CustomFullViewLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FULLVIEW,
			GD_TEMPLATE_LAYOUT_FULLVIEW_LINK,
			GD_TEMPLATE_LAYOUT_FULLVIEW_HIGHLIGHT,
			GD_TEMPLATE_LAYOUT_FULLVIEW_WEBPOST,
			GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW,
			GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_LINK,
			GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT,
			GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_WEBPOST,
			GD_TEMPLATE_SINGLELAYOUT_FULLVIEW,
			GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_LINK,
			GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT,
			GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_WEBPOST,
		);
	}

	function get_footer_templates($template_id) {

		$ret = parent::get_footer_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_LINK:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_LINK:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_LINK:

			case GD_TEMPLATE_LAYOUT_FULLVIEW_WEBPOST:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_WEBPOST:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_WEBPOST:

				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL;
				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION;
				$ret[] = GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_FULLVIEW;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS;
				break;

			case GD_TEMPLATE_LAYOUT_FULLVIEW_HIGHLIGHT:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT:

				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL;
				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION;
				$ret[] = GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER;
				$ret[] = GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS;
				break;
		}

		return $ret;
	}

	function get_sidebar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW:
			case GD_TEMPLATE_LAYOUT_FULLVIEW_LINK:
			case GD_TEMPLATE_LAYOUT_FULLVIEW_HIGHLIGHT:
			case GD_TEMPLATE_LAYOUT_FULLVIEW_WEBPOST:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_LINK:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_WEBPOST:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_LINK:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_WEBPOST:

				$sidebars = array(
					GD_TEMPLATE_LAYOUT_FULLVIEW => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL,
					GD_TEMPLATE_LAYOUT_FULLVIEW_LINK => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK,
					GD_TEMPLATE_LAYOUT_FULLVIEW_HIGHLIGHT => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT,
					GD_TEMPLATE_LAYOUT_FULLVIEW_WEBPOST => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_WEBPOST,
					GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL,
					GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_LINK => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK,
					GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT,
					GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_WEBPOST => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_WEBPOST,
					GD_TEMPLATE_SINGLELAYOUT_FULLVIEW => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL,
					GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_LINK => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_LINK,
					GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_HIGHLIGHT,
					GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_WEBPOST => GD_TEMPLATE_LAYOUT_POSTSIDEBARCOMPACT_HORIZONTAL_WEBPOST,
				);

				return $sidebars[$template_id];
		}

		return parent::get_sidebar_template($template_id);
	}

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_HIGHLIGHT:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT:

				$ret[GD_JS_CLASSES/*'classes'*/]['content'] = 'well readable';
				break;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_FULLVIEW_LINK:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_LINK:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_LINK:

			case GD_TEMPLATE_LAYOUT_FULLVIEW_WEBPOST:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_WEBPOST:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_WEBPOST:

				$this->merge_att(GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW, $atts, 'previoustemplates-ids', array(
					'data-lazyloadingspinner-target' => GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
				));
				break;

			case GD_TEMPLATE_LAYOUT_FULLVIEW_HIGHLIGHT:
			case GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW_HIGHLIGHT:
			case GD_TEMPLATE_SINGLELAYOUT_FULLVIEW_HIGHLIGHT:

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
new GD_Template_Processor_CustomFullViewLayouts();