<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_BareSimpleViewPreviewPostLayoutsBase extends GD_Template_Processor_CustomPreviewPostLayoutsBase {

	function show_date($template_id) {

		return true;
	}

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_POSTTHUMB_LINKSELFCROPPEDFEED_VOLUNTEER;//GD_TEMPLATE_LAYOUTWRAPPER_POSTTHUMB_CROPPEDFEED_VOLUNTEER;

		return $ret;
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
}
