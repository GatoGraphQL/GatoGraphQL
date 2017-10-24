<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CarouselInnersBase extends GD_Template_Processor_StructureInnersBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_CAROUSEL_INNER;
	}

	function get_layout_grid($template_id, $atts) {

		return array(
			'divider' => 1,
			'class' => '',
		);
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['layout-grid'] = $this->get_att($template_id, $atts, 'layout-grid');

		// Comment Leo 03/07/2017: move the 'row' class out of the .tmpl, so it can be converted to style for the automatedemails
		$ret[GD_JS_CLASSES/*'classes'*/]['layoutgrid-wrapper'] = 'row';

		// Comment Leo 20/10/2017: this approach below doesn't work, because the configuration is saved, so that when appending items
		// will also paint the newcomer item as 'active' (since it has @index = 0) so then the carousel will have 2 active items
		// because of this, I introduced helper iffirstload, so that it only works when first loading the website, otherwise it doesn't,
		// which is ok since JS will add the class anyway
		// // If we are loading the frame, then we can already add class 'active' to the first item
		// // Do it so that content is already visible immediately
		// // If not no need, since JS will add it already
		// // We need JS to execute the logic to add 'active' when filtering/appending items on the carousel, 
		// // so that there will always be one with active status (JS knows when it's the case, back-end doesn't know)
		// // that's why we don't add 'active' always here
		// if (GD_TemplateManager_Utils::loading_frame()) {
		// 	$ret[GD_JS_CLASSES/*'classes'*/]['first-item'] = 'active';
		// }

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->add_att($template_id, $atts, 'layout-grid', $this->get_layout_grid($template_id, $atts));

		// Needed for the automated emails
		$this->append_att($template_id, $atts, 'class', 'carousel-elem');

		// Comment Leo 03/07/2017: if the layout-grid has a class, add it to the module class
		// This is done so that the name of the class can be converted to style, for the automatedemails
		if ($layout_grid = $this->get_att($template_id, $atts, 'layout-grid')) {
			
			if ($class = $layout_grid['class']) {

				$this->append_att($template_id, $atts, 'class', $class);
			}
		}


		return parent::init_atts($template_id, $atts);
	}
}
