<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_UPDATEDATA', PoP_ServerUtils::get_template_definition('contentinner-dataquery-allcontent-updatedata'));
define ('GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLUSERS_UPDATEDATA', PoP_ServerUtils::get_template_definition('contentinner-dataquery-allusers-updatedata'));
define ('GD_TEMPLATE_CONTENTINNER_DATAQUERY_COMMENTS_UPDATEDATA', PoP_ServerUtils::get_template_definition('contentinner-dataquery-comments-updatedata'));
define ('GD_TEMPLATE_CONTENTINNER_DATAQUERY_TAGS_UPDATEDATA', PoP_ServerUtils::get_template_definition('contentinner-dataquery-tags-updatedata'));
define ('GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_REQUESTLAYOUTS', PoP_ServerUtils::get_template_definition('contentinner-dataquery-allcontent-requestlayouts'));
define ('GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLUSERS_REQUESTLAYOUTS', PoP_ServerUtils::get_template_definition('contentinner-dataquery-allusers-requestlayouts'));
define ('GD_TEMPLATE_CONTENTINNER_DATAQUERY_COMMENTS_REQUESTLAYOUTS', PoP_ServerUtils::get_template_definition('contentinner-dataquery-comments-requestlayouts'));
define ('GD_TEMPLATE_CONTENTINNER_DATAQUERY_TAGS_REQUESTLAYOUTS', PoP_ServerUtils::get_template_definition('contentinner-dataquery-tags-requestlayouts'));
define ('GD_TEMPLATE_CONTENTINNER_PAGECONTENT', PoP_ServerUtils::get_template_definition('contentinner-getpop-pagecontent'));

// define ('GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_COMMENTS', 'contentinner-dataquery-allcontent-comments');

class GD_Template_Processor_MultipleContentInners extends GD_Template_Processor_ContentMultipleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_UPDATEDATA,
			GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLUSERS_UPDATEDATA,
			GD_TEMPLATE_CONTENTINNER_DATAQUERY_COMMENTS_UPDATEDATA,
			GD_TEMPLATE_CONTENTINNER_DATAQUERY_TAGS_UPDATEDATA,
			GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_REQUESTLAYOUTS,
			GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLUSERS_REQUESTLAYOUTS,
			GD_TEMPLATE_CONTENTINNER_DATAQUERY_COMMENTS_REQUESTLAYOUTS,
			GD_TEMPLATE_CONTENTINNER_DATAQUERY_TAGS_REQUESTLAYOUTS,
			// GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_COMMENTS,
			GD_TEMPLATE_CONTENTINNER_PAGECONTENT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_UPDATEDATA:
			case GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLUSERS_UPDATEDATA:
			case GD_TEMPLATE_CONTENTINNER_DATAQUERY_COMMENTS_UPDATEDATA:
			case GD_TEMPLATE_CONTENTINNER_DATAQUERY_TAGS_UPDATEDATA:

				$ret[] = GD_TEMPLATE_LAYOUT_DATAQUERY_UPDATEDATA;
				break;

			case GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_REQUESTLAYOUTS:
			case GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLUSERS_REQUESTLAYOUTS:
			case GD_TEMPLATE_CONTENTINNER_DATAQUERY_COMMENTS_UPDATEDATA:
			case GD_TEMPLATE_CONTENTINNER_DATAQUERY_TAGS_REQUESTLAYOUTS:

				$ret[] = GD_TEMPLATE_LAYOUT_DATAQUERY_REQUESTLAYOUTS;
				break;

			// case GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_COMMENTS:

			// 	$ret[] = GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS;
			// 	break;

			default:

				$layouts = array(
					GD_TEMPLATE_CONTENTINNER_PAGECONTENT => GD_TEMPLATE_LAYOUT_CONTENT_PAGE,
				);
				if ($layout = $layouts[$template_id]) {
					$ret[] = $layout;
				}
				break;
		}

		return $ret;
	}

	// function init_atts($template_id, &$atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_CONTENTINNER_DATAQUERY_ALLCONTENT_COMMENTS:

	// 			$this->add_att(GD_TEMPLATE_SUBCOMPONENT_POSTCOMMENTS, $atts, 'allow-append-content', false);
	// 			break;
	// 	}
		
	// 	return parent::init_atts($template_id, $atts);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MultipleContentInners();