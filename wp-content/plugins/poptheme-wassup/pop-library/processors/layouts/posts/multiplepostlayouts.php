<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_NAVIGATOR', PoP_ServerUtils::get_template_definition('layout-multiplepost-navigator'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_ADDONS', PoP_ServerUtils::get_template_definition('layout-multiplepost-addons'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_DETAILS', PoP_ServerUtils::get_template_definition('layout-multiplepost-details'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_THUMBNAIL', PoP_ServerUtils::get_template_definition('layout-multiplepost-thumbnail'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LIST', PoP_ServerUtils::get_template_definition('layout-multiplepost-list'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LINE', PoP_ServerUtils::get_template_definition('layout-multiplepost-line'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_RELATED', PoP_ServerUtils::get_template_definition('layout-multiplepost-related'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_EDIT', PoP_ServerUtils::get_template_definition('layout-multiplepost-edit'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-multiplepost-simpleview'));
define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_FULLVIEW', PoP_ServerUtils::get_template_definition('layout-multiplepost-fullview'));
// define ('GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEPOST_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-authormultiplepost-simpleview'));
define ('GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEPOST_FULLVIEW', PoP_ServerUtils::get_template_definition('layout-authormultiplepost-fullview'));
// define ('GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEPOST_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-singlemultiplepost-simpleview'));
define ('GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEPOST_FULLVIEW', PoP_ServerUtils::get_template_definition('layout-singlemultiplepost-fullview'));

define ('GD_TEMPLATE_LAYOUT_MULTIPLEPOST_SIMPLEVIEW_CUSTOMLAYOUTS', PoP_ServerUtils::get_template_definition('layout-multiplepost-simpleview-customlayouts'));

class GD_Template_Processor_MultiplePostLayouts extends GD_Template_Processor_MultiplePostLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_ADDONS,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_DETAILS,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LIST,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LINE,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_RELATED,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_EDIT,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_FULLVIEW,
			// GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEPOST_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEPOST_FULLVIEW,
			// GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEPOST_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEPOST_FULLVIEW,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_SIMPLEVIEW_CUSTOMLAYOUTS,
		);
	}

	function get_default_layout($template_id) {

		$defaults = array(
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_NAVIGATOR => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_NAVIGATOR, //GD_TEMPLATE_LAYOUT_PREVIEWPOST_NAVIGATOR,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_ADDONS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_ADDONS, //GD_TEMPLATE_LAYOUT_PREVIEWPOST_ADDONS,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_DETAILS => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_DETAILS, //GD_TEMPLATE_LAYOUT_PREVIEWPOST_DETAILS,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_THUMBNAIL => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_THUMBNAIL, //GD_TEMPLATE_LAYOUT_PREVIEWPOST_THUMBNAIL,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LIST => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_LIST, //GD_TEMPLATE_LAYOUT_PREVIEWPOST_LIST,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LINE => GD_TEMPLATE_LAYOUT_PREVIEWPOST_LINE,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_RELATED => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_RELATED, //GD_TEMPLATE_LAYOUT_PREVIEWPOST_RELATED,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_EDIT => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_EDIT, //GD_TEMPLATE_LAYOUT_PREVIEWPOST_EDIT,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_SIMPLEVIEW => GD_TEMPLATE_LAYOUT_PREVIEWPOST_WEBPOST_SIMPLEVIEW, //GD_TEMPLATE_LAYOUT_PREVIEWPOST_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUT_MULTIPLEPOST_FULLVIEW => GD_TEMPLATE_LAYOUT_FULLVIEW,
			GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEPOST_FULLVIEW => GD_TEMPLATE_AUTHORLAYOUT_FULLVIEW,
			GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEPOST_FULLVIEW => GD_TEMPLATE_SINGLELAYOUT_FULLVIEW,
		);

		if ($default = $defaults[$template_id]) {

			return $default;
		}

		return parent::get_default_layout($template_id);
	}

	function get_multiple_layouts($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_NAVIGATOR:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:navigator',
					array()
					// array(
					// 'attachment-'.POPTHEME_WASSUP_MLA_CAT_RESOURCES => GD_TEMPLATE_LAYOUTMEDIA,
					// )
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_ADDONS:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:addons',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_DETAILS:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:details',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_THUMBNAIL:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:thumbnail',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LIST:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:list',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_LINE:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:line',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_RELATED:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:related',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_EDIT:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:edit',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_SIMPLEVIEW:
			// case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEPOST_SIMPLEVIEW:
			// case GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEPOST_SIMPLEVIEW:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:simpleview',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_FULLVIEW:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:fullview',
					array()
				);

			// case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEPOST_SIMPLEVIEW:

			// 	return apply_filters(
			// 		'GD_Template_Processor_MultiplePostLayouts:layouts:authorsimpleview',
			// 		array()
			// 	);

			case GD_TEMPLATE_LAYOUT_AUTHORMULTIPLEPOST_FULLVIEW:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:authorfullview',
					array()
				);

			// case GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEPOST_SIMPLEVIEW:

			// 	return apply_filters(
			// 		'GD_Template_Processor_MultiplePostLayouts:layouts:singlesimpleview',
			// 		array()
			// 	);

			case GD_TEMPLATE_LAYOUT_SINGLEMULTIPLEPOST_FULLVIEW:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:layouts:singlefullview',
					array()
				);

			case GD_TEMPLATE_LAYOUT_MULTIPLEPOST_SIMPLEVIEW_CUSTOMLAYOUTS:

				return apply_filters(
					'GD_Template_Processor_MultiplePostLayouts:simpleview:customlayouts',
					array()
				);
		}

		return parent::get_multiple_layouts($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_MultiplePostLayouts();