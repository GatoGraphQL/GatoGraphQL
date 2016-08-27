<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomSimpleViewPreviewPostLayoutsBase extends GD_Template_Processor_CustomPreviewPostLayoutsBase {

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

	function show_date($template_id) {

		return true;
	}

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER;//GD_TEMPLATE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER;

		return $ret;
	}

	function get_aftercontent_layouts($template_id) {

		$ret = parent::get_aftercontent_layouts($template_id);

		return array_merge(
			$ret,
			$this->get_simpleviewfeed_bottom_layouts($template_id)
		);
	}

	function get_author_avatar_template($template_id) {

		return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR82;
	}

	function get_content_template($template_id) {

		return GD_TEMPLATE_MULTICOMPONENT_SIMPLEVIEW_POSTCONTENT;//GD_TEMPLATE_LAYOUT_MAXHEIGHT_POSTCONTENT;
	}
	

	function get_post_thumb_template($template_id) {

		return null;
	}

	function get_title_htmlmarkup($template_id, $atts) {

		return 'h3';
	}

	function author_positions($template_id) {

		return array(
			GD_CONSTANT_AUTHORPOSITION_ABOVETITLE
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

	function horizontal_layout($template_id) {

		return true;
	}
	

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret[GD_JS_CLASSES/*'classes'*/]['wrapper'] = 'row';
		$ret[GD_JS_CLASSES/*'classes'*/]['thumb-wrapper'] = 'col-sm-2 avatar';
		$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] = 'col-sm-10 simpleview';
		$ret[GD_JS_CLASSES/*'classes'*/]['aftercontent-inner'] = 'col-sm-12';

		return $ret;
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
