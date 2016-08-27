<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_CONTENTINNER_USEROPINIONATEDVOTEPOSTINTERACTION', PoP_ServerUtils::get_template_definition('contentinner-useropinionatedvotepostinteraction'));
define ('GD_TEMPLATE_CONTENTINNER_OPINIONATEDVOTESINGLE', PoP_ServerUtils::get_template_definition('contentinner-opinionatedvotesingle'));

class VotingProcessors_Template_Processor_SingleContentInners extends GD_Template_Processor_ContentSingleInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_CONTENTINNER_USEROPINIONATEDVOTEPOSTINTERACTION,
			GD_TEMPLATE_CONTENTINNER_OPINIONATEDVOTESINGLE,
		);
	}

	protected function get_commentssingle_layouts($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_USEROPINIONATEDVOTEPOSTINTERACTION:
				
				$layouts = array(
					// GD_TEMPLATE_LAYOUTWRAPPER_SUBJUGATEDPOSTCONCLUSIONSIDEBAR_HORIZONTAL,
					GD_TEMPLATE_LAYOUTWRAPPER_USEROPINIONATEDVOTEPOSTINTERACTION,
					GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
					GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS,
				);
				break;
		}

		return apply_filters('VotingProcessors_Template_Processor_SingleContentInners:commentssingle_layouts', $layouts, $template_id);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_CONTENTINNER_USEROPINIONATEDVOTEPOSTINTERACTION:

				$ret = array_merge(
					$ret,
					$this->get_commentssingle_layouts($template_id)
				);
				break;

			case GD_TEMPLATE_CONTENTINNER_OPINIONATEDVOTESINGLE:

				$ret[] = GD_TEMPLATE_LAYOUTSTANCE;
				$ret[] = GD_TEMPLATE_LAYOUT_CONTENT_POST;//GD_TEMPLATE_LAYOUTSINGLE;
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
		
			case GD_TEMPLATE_CONTENTINNER_USEROPINIONATEDVOTEPOSTINTERACTION:

				// $this->add_att(GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT_APPENDABLE, $atts, 'show-lazyloading-spinner', true);
				$this->merge_att(GD_TEMPLATE_LAZYSUBCOMPONENT_NOHEADERPOSTCOMMENTS, $atts, 'previoustemplates-ids', array(
					'data-lazyloadingspinner-target' => GD_TEMPLATE_CODEWRAPPER_LAZYLOADINGSPINNER,
				));
				$this->append_att($template_id, $atts, 'class', 'userpostinteraction-single');
				break;
		
			case GD_TEMPLATE_CONTENTINNER_OPINIONATEDVOTESINGLE:

				// $this->append_att(GD_TEMPLATE_WIDGETWRAPPER_REFERENCES, $atts, 'class', 'wrapper-references-single');

				global $post;
				$class = 'alert';
				if (POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_PRO && has_category(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_PRO, $post->ID)) {
					$class .= ' alert-success';
				}
				elseif (POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_AGAINST && has_category(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_AGAINST, $post->ID)) {
					$class .= ' alert-danger';
				}
				elseif (POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_NEUTRAL && has_category(POPTHEME_WASSUP_VOTINGPROCESSORS_CAT_OPINIONATEDVOTES_NEUTRAL, $post->ID)) {
					$class .= ' alert-info';
				}
				$this->append_att($template_id, $atts, 'runtime-class', $class);
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_SingleContentInners();