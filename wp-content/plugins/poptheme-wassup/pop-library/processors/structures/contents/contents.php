<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENT_AUTHOR', PoP_ServerUtils::get_template_definition('content-author'));
// define ('GD_TEMPLATE_CONTENT_TAG', PoP_ServerUtils::get_template_definition('content-tag'));
define ('GD_TEMPLATE_CONTENT_SINGLE', PoP_ServerUtils::get_template_definition('content-single'));
define ('GD_TEMPLATE_CONTENT_USERPOSTINTERACTION', PoP_ServerUtils::get_template_definition('content-userpostinteraction'));
define ('GD_TEMPLATE_CONTENT_POSTHEADER', PoP_ServerUtils::get_template_definition('content-postheader'));
define ('GD_TEMPLATE_CONTENT_USERHEADER', PoP_ServerUtils::get_template_definition('content-userheader'));
// define ('GD_TEMPLATE_CONTENT_POSTCONCLUSION', PoP_ServerUtils::get_template_definition('content-postconclusion'));

define ('GD_TEMPLATE_CONTENT_DATAQUERY_ALLCONTENT_UPDATEDATA', PoP_ServerUtils::get_template_definition('content-dataquery-allcontent-updatedata'));
define ('GD_TEMPLATE_CONTENT_DATAQUERY_ALLUSERS_UPDATEDATA', PoP_ServerUtils::get_template_definition('content-dataquery-allusers-updatedata'));
define ('GD_TEMPLATE_CONTENT_DATAQUERY_COMMENTS_UPDATEDATA', PoP_ServerUtils::get_template_definition('content-dataquery-comments-updatedata'));
define ('GD_TEMPLATE_CONTENT_DATAQUERY_TAGS_UPDATEDATA', PoP_ServerUtils::get_template_definition('content-dataquery-tags-updatedata'));
define ('GD_TEMPLATE_CONTENT_DATAQUERY_ALLCONTENT_REQUESTLAYOUTS', PoP_ServerUtils::get_template_definition('content-dataquery-allcontent-requestlayouts'));
define ('GD_TEMPLATE_CONTENT_DATAQUERY_ALLUSERS_REQUESTLAYOUTS', PoP_ServerUtils::get_template_definition('content-dataquery-allusers-requestlayouts'));
define ('GD_TEMPLATE_CONTENT_DATAQUERY_COMMENTS_REQUESTLAYOUTS', PoP_ServerUtils::get_template_definition('content-dataquery-comments-requestlayouts'));
define ('GD_TEMPLATE_CONTENT_DATAQUERY_TAGS_REQUESTLAYOUTS', PoP_ServerUtils::get_template_definition('content-dataquery-tags-requestlayouts'));
// define ('GD_TEMPLATE_CONTENT_DATAQUERY_ALLCONTENT_COMMENTS', PoP_ServerUtils::get_template_definition('content-dataquery-allcontent-comments'));

class GD_Template_Processor_Contents extends GD_Template_Processor_ContentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENT_AUTHOR,
			// GD_TEMPLATE_CONTENT_TAG,
			GD_TEMPLATE_CONTENT_SINGLE,
			GD_TEMPLATE_CONTENT_USERPOSTINTERACTION,
			GD_TEMPLATE_CONTENT_POSTHEADER,
			GD_TEMPLATE_CONTENT_USERHEADER,
			GD_TEMPLATE_CONTENT_DATAQUERY_ALLCONTENT_UPDATEDATA,
			GD_TEMPLATE_CONTENT_DATAQUERY_ALLUSERS_UPDATEDATA,
			GD_TEMPLATE_CONTENT_DATAQUERY_COMMENTS_UPDATEDATA,
			GD_TEMPLATE_CONTENT_DATAQUERY_TAGS_UPDATEDATA,
			GD_TEMPLATE_CONTENT_DATAQUERY_ALLCONTENT_REQUESTLAYOUTS,
			GD_TEMPLATE_CONTENT_DATAQUERY_ALLUSERS_REQUESTLAYOUTS,
			GD_TEMPLATE_CONTENT_DATAQUERY_COMMENTS_REQUESTLAYOUTS,
			GD_TEMPLATE_CONTENT_DATAQUERY_TAGS_REQUESTLAYOUTS,
			// GD_TEMPLATE_CONTENT_DATAQUERY_ALLCONTENT_COMMENTS,
			// GD_TEMPLATE_CONTENT_POSTCONCLUSION,
		);
	}
	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_CONTENT_AUTHOR => GD_TEMPLATE_CONTENTINNER_AUTHOR,
			// GD_TEMPLATE_CONTENT_TAG => GD_TEMPLATE_CONTENTINNER_TAG,
			// GD_TEMPLATE_CONTENT_SINGLE => GD_TEMPLATE_CONTENTINNER_SINGLE,		
			// GD_TEMPLATE_CONTENT_USERPOSTINTERACTION => GD_TEMPLATE_CONTENTINNER_USERPOSTINTERACTION,		
			GD_TEMPLATE_CONTENT_POSTHEADER => GD_TEMPLATE_CONTENTINNER_POSTHEADER,		
			GD_TEMPLATE_CONTENT_USERHEADER => GD_TEMPLATE_CONTENTINNER_USERHEADER,
			GD_TEMPLATE_CONTENT_DATAQUERY_ALLCONTENT_UPDATEDATA => GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_UPDATEDATA,
			GD_TEMPLATE_CONTENT_DATAQUERY_ALLUSERS_UPDATEDATA => GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLUSERS_UPDATEDATA,
			GD_TEMPLATE_CONTENT_DATAQUERY_COMMENTS_UPDATEDATA => GD_TEMPLATE_CONTENTINNER_DATAQUERY_COMMENTS_UPDATEDATA,
			GD_TEMPLATE_CONTENT_DATAQUERY_TAGS_UPDATEDATA => GD_TEMPLATE_CONTENTINNER_DATAQUERY_TAGS_UPDATEDATA,
			GD_TEMPLATE_CONTENT_DATAQUERY_ALLCONTENT_REQUESTLAYOUTS => GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_REQUESTLAYOUTS,
			GD_TEMPLATE_CONTENT_DATAQUERY_ALLUSERS_REQUESTLAYOUTS => GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLUSERS_REQUESTLAYOUTS,
			GD_TEMPLATE_CONTENT_DATAQUERY_COMMENTS_REQUESTLAYOUTS => GD_TEMPLATE_CONTENTINNER_DATAQUERY_COMMENTS_REQUESTLAYOUTS,
			GD_TEMPLATE_CONTENT_DATAQUERY_TAGS_REQUESTLAYOUTS => GD_TEMPLATE_CONTENTINNER_DATAQUERY_TAGS_REQUESTLAYOUTS,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		$hookable = array(
			GD_TEMPLATE_CONTENT_SINGLE,
			GD_TEMPLATE_CONTENT_USERPOSTINTERACTION,
		);
		if (in_array($template_id, $hookable)) {

			global $post;
			if ($template_id == GD_TEMPLATE_CONTENT_SINGLE && POPTHEME_WASSUP_CAT_WEBPOSTLINKS && has_category(POPTHEME_WASSUP_CAT_WEBPOSTLINKS, $post)) {

				$inner = GD_TEMPLATE_CONTENTINNER_LINKSINGLE;
			}
			else {

				$cat = gd_get_the_main_category();
				$inners = array(
					GD_TEMPLATE_CONTENT_SINGLE => array(
						POPTHEME_WASSUP_CAT_HIGHLIGHTS => GD_TEMPLATE_CONTENTINNER_HIGHLIGHTSINGLE,
						// POPTHEME_WASSUP_CAT_WEBPOSTLINKS => GD_TEMPLATE_CONTENTINNER_LINKSINGLE,
						'default' => GD_TEMPLATE_CONTENTINNER_SINGLE,
					),
					GD_TEMPLATE_CONTENT_USERPOSTINTERACTION => array(
						POPTHEME_WASSUP_CAT_HIGHLIGHTS => GD_TEMPLATE_CONTENTINNER_USERHIGHLIGHTPOSTINTERACTION,
						'default' => GD_TEMPLATE_CONTENTINNER_USERPOSTINTERACTION,
					),
				);
				$inner = $inners[$template_id][$cat] ? $inners[$template_id][$cat] : $inners[$template_id]['default'];
			}

			return apply_filters('GD_Template_Processor_Contents:inner_template', $inner, $template_id);
		}

		return parent::get_inner_template($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_CONTENT_SINGLE:

				$this->append_att($template_id, $atts, 'class', 'content-single');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_Contents();