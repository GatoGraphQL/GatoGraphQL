<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomSimpleViewPreviewPostLayoutsBase extends GD_Template_Processor_BareSimpleViewPreviewPostLayoutsBase {

	protected function get_simpleviewfeed_bottom_layouts($template_id) {

		$layouts = array();

		$layouts[] = GD_TEMPLATE_POSTSOCIALMEDIA_SIMPLEVIEW_VOLUNTEERPOSTWRAPPER;//GD_TEMPLATE_POSTSOCIALMEDIA_POSTWRAPPER;
		$layouts[] = GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION;

		// Add the highlights and the referencedby. Lazy or not lazy?
		if (PoPTheme_Wassup_Utils::feed_simpleview_lazyload()) {
			$layouts[] = GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY_LAZYSIMPLEVIEW;
			// $layouts[] = GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER;
			// $layouts[] = GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW;
			// $layouts[] = GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW;
			// $layouts[] = GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS;
		}
		else {
			$layouts[] = GD_TEMPLATE_MULTICOMPONENT_USERPOSTACTIVITY_SIMPLEVIEW;
			// $layouts[] = GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW;
			// $layouts[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY_SIMPLEVIEW;
			// $layouts[] = GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS;
		}

		// Allow to override. Eg: TPP Debate website adds the Stance Counter
		$layouts = apply_filters('GD_Template_Processor_CustomPreviewPostLayoutsBase:simpleviewfeed_bottom_layouts', $layouts, $template_id);

		return $layouts;
	}

	function get_aftercontent_layouts($template_id) {

		$ret = parent::get_aftercontent_layouts($template_id);

		return array_merge(
			$ret,
			$this->get_simpleviewfeed_bottom_layouts($template_id)
		);
	}

	function get_title_beforeauthors($template_id, $atts) {

		return array(
			'abovetitle' => sprintf(
				'<small class="visible-link inline">%s</small>',
				__('link by', 'poptheme-wassup')
			)
		);
	}

	function init_atts($template_id, &$atts) {		

		if (PoPTheme_Wassup_Utils::feed_simpleview_lazyload()) {
			$this->merge_att(GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_SIMPLEVIEW, $atts, 'previoustemplates-ids', array(
				'data-lazyloadingspinner-target' => GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
			));
		}

		return parent::init_atts($template_id, $atts);
	}
}
