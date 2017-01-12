<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR', PoP_ServerUtils::get_template_definition('layout-previewpost-opinionatedvote-contentauthor'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED', PoP_ServerUtils::get_template_definition('layout-previewpost-opinionatedvote-contentreferenced'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED', PoP_ServerUtils::get_template_definition('layout-previewpost-opinionatedvote-contentauthorreferenced'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR', PoP_ServerUtils::get_template_definition('layout-previewpost-opinionatedvote-navigator'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL', PoP_ServerUtils::get_template_definition('layout-previewpost-opinionatedvote-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT', PoP_ServerUtils::get_template_definition('layout-previewpost-opinionatedvote-edit'));

class VotingProcessors_Template_Processor_CustomPreviewPostLayouts extends GD_Template_Processor_CustomPreviewPostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT,
		);
	}

	// function get_content_maxlength($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:

	// 			return 400;
	// 	}

	// 	return parent::get_content_maxlength($template_id, $atts);
	// }
	// function get_content_maxheight($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:

	// 			return 400;
	// 	}

	// 	return parent::get_content_maxheight($template_id, $atts);
	// }

	function get_url_field($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:

				return 'edit-url';
		}

		return parent::get_url_field($template_id);
	}

	function get_linktarget($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:

				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					
					return GD_URLPARAM_TARGET_ADDONS;
				}
				break;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	function get_quicklinkgroup_bottom($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:

				return GD_TEMPLATE_QUICKLINKGROUP_OPINIONATEDVOTEEDIT;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:

				return GD_TEMPLATE_QUICKLINKGROUP_OPINIONATEDVOTECONTENT;
		}

		return parent::get_quicklinkgroup_bottom($template_id);
	}

	function show_posttitle($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:

				return false;
		}

		return parent::show_posttitle($template_id);
	}

	// function show_content($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:

	// 			return true;
	// 	}

	// 	return parent::show_content($template_id);
	// }
	function get_content_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:

				return GD_TEMPLATE_LAYOUT_CONTENT_POSTCOMPACT;
		}

		return parent::get_content_template($template_id);
	}

	function get_abovecontent_layouts($template_id) {

		$ret = parent::get_abovecontent_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:

				$ret[] = GD_TEMPLATE_LAYOUTSTANCE;
				break;
		}

		return $ret;
	}

	function get_author_avatar_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:

				return GD_TEMPLATE_LAYOUTPOST_AUTHORAVATAR60;
		}

		return parent::get_author_avatar_template($template_id);
	}
	
	function get_bottom_layouts($template_id) {

		$ret = parent::get_bottom_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCES_ADDONS;
				break;
		}

		return $ret;
	}

	function get_belowcontent_layouts($template_id) {

		$ret = parent::get_belowcontent_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCES_REFERENCEDPOSTTITLE;
				break;

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:

				$ret[] = GD_TEMPLATE_LAYOUT_REFERENCES_AUTHORREFERENCEDPOSTTITLE;
				break;
		}

		return $ret;
	}

	function get_post_thumb_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:

				return null;
		}

		return parent::get_post_thumb_template($template_id);
	}

	function author_positions($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:

				return array(
					GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT
				);

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_EDIT:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
			
				return array();
		}

		return parent::author_positions($template_id);
	}

	// function get_quicklinkgroup_top($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
	// 		case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			// case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:

	// 			return GD_TEMPLATE_QUICKLINKGROUP_POST;
	// 	}

	// 	return parent::get_quicklinkgroup_top($template_id);
	// }

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:

				$ret[GD_JS_CLASSES/*'classes'*/]['authors'] = 'inline';
				$ret[GD_JS_CLASSES/*'classes'*/]['belowcontent'] = 'inline';
				$ret[GD_JS_CLASSES/*'classes'*/]['belowcontent-inner'] = 'inline';
				break;
		}

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:

				$ret[GD_JS_CLASSES/*'classes'*/]['wrapper'] .= ' media';
				$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] .= ' media-body';
				$ret[GD_JS_CLASSES/*'classes'*/]['thumb-wrapper'] .= ' pull-left';
				$ret[GD_JS_CLASSES/*'classes'*/]['content'] .= ' readable';
				break;
		}

		return $ret;
	}

	function get_title_beforeauthors($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:

				return array(
					'belowcontent' => sprintf(
						'<span class="pop-pulltextright">%s</span>',
						__('—', 'poptheme-wassup-votingprocessors')
					)
				);
		}

		return parent::get_title_beforeauthors($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_CONTENTAUTHORREFERENCED:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_NAVIGATOR:
			case GD_TEMPLATE_LAYOUT_PREVIEWPOST_OPINIONATEDVOTE_THUMBNAIL:

				$this->append_att($template_id, $atts, 'class', 'alert alert-opinionatedvote');
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomPreviewPostLayouts();