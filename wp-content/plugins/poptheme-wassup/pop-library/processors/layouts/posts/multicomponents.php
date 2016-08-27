<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION', PoP_ServerUtils::get_template_definition('multicomponent-userhighlightpostinteraction'));
define ('GD_TEMPLATE_MULTICOMPONENT_USERPOSTINTERACTION', PoP_ServerUtils::get_template_definition('multicomponent-userpostinteraction'));

class Wassup_Template_Processor_MultipleComponentLayouts extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION,
			GD_TEMPLATE_MULTICOMPONENT_USERPOSTINTERACTION,
		);
	}

	protected function get_userpostinteraction_layouts($template_id) {

		switch ($template_id) {

			// Highlights: it has a different set-up
			case GD_TEMPLATE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION:
				
				$layouts = array(
					// GD_TEMPLATE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL,
					GD_TEMPLATE_LAYOUTWRAPPER_USERHIGHLIGHTPOSTINTERACTION,
					GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
					GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS,
				);
				break;
			
			case GD_TEMPLATE_MULTICOMPONENT_USERPOSTINTERACTION:

				$layouts = array(
					// GD_TEMPLATE_LAYOUTWRAPPER_POSTCONCLUSIONSIDEBAR_HORIZONTAL,
					GD_TEMPLATE_LAYOUTWRAPPER_USERPOSTINTERACTION,
					GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
					GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW, // Loading Lazy. Non-lazy: GD_TEMPLATE_WIDGETWRAPPER_HIGHLIGHTREFERENCEDBY;
					GD_TEMPLATE_LAZYSUBCOMPONENT_REFERENCEDBY_FULLVIEW, // Loading Lazy. Non-lazy: GD_TEMPLATE_WIDGETWRAPPER_REFERENCEDBY;
					GD_TEMPLATE_LAZYSUBCOMPONENT_POSTCOMMENTS, // Loading Lazy. Non-lazy: GD_TEMPLATE_WIDGETWRAPPER_POSTCOMMENTS;
				);				
				break;
		}

		// Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
		return apply_filters('Wassup_Template_Processor_MultipleComponentLayouts:userpostinteraction_layouts', $layouts, $template_id);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION:
			case GD_TEMPLATE_MULTICOMPONENT_USERPOSTINTERACTION:

				$ret = array_merge(
					$ret,
					$this->get_userpostinteraction_layouts($template_id)
				);				
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION:

				// Highlight cannot recommend
				// $this->append_att($template_id, $atts, 'class', 'pop-norecommend');
				
				// $this->add_att(GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE, $atts, 'show-lazyloading-spinner', true);
				$this->merge_att(GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS, $atts, 'previoustemplates-ids', array(
					'data-lazyloadingspinner-target' => GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
				));
				break;
				
			case GD_TEMPLATE_MULTICOMPONENT_USERPOSTINTERACTION:

				$this->merge_att(GD_TEMPLATE_LAZYSUBCOMPONENT_HIGHLIGHTREFERENCEDBY_FULLVIEW, $atts, 'previoustemplates-ids', array(
					'data-lazyloadingspinner-target' => GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
				));
				// $this->add_att(GD_TEMPLATE_CONTENTLAYOUT_HIGHLIGHTREFERENCEDBY_APPENDABLE, $atts, 'show-lazyloading-spinner', true);
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_MULTICOMPONENT_USERHIGHLIGHTPOSTINTERACTION:
			case GD_TEMPLATE_MULTICOMPONENT_USERPOSTINTERACTION:

				$this->append_att($template_id, $atts, 'class', 'userpostinteraction-single');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_MultipleComponentLayouts();