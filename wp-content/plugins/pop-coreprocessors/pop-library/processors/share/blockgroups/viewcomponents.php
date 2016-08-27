<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/
define ('GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL', PoP_ServerUtils::get_template_definition('blockgroup-embed-modal'));
define ('GD_TEMPLATE_BLOCKGROUP_COPYSEARCHURL_MODAL', PoP_ServerUtils::get_template_definition('blockgroup-copysearchurl-modal'));

class GD_Template_Processor_ShareModalBlockGroups extends GD_Template_Processor_FormModalViewComponentBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL,
			GD_TEMPLATE_BLOCKGROUP_COPYSEARCHURL_MODAL,
		);
	}

	function get_blockgroup_blocks($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL:

				return array(
					GD_TEMPLATE_BLOCK_EMBED
				);

			case GD_TEMPLATE_BLOCKGROUP_COPYSEARCHURL_MODAL:

				return array(
					GD_TEMPLATE_BLOCK_COPYSEARCHURL
				);
		}

		return parent::get_blockgroup_blocks($template_id);
	}

	function get_header_title($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL:

				return __('Embed:', 'pop-coreprocessors');

			case GD_TEMPLATE_BLOCKGROUP_COPYSEARCHURL_MODAL:

				return __('Copy Search URL:', 'pop-coreprocessors');
		}

		return parent::get_header_title($template_id);
	}

	function get_icon($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL:

				return 'fa-code';
		
			case GD_TEMPLATE_BLOCKGROUP_COPYSEARCHURL_MODAL:

				return 'fa-link';
		}

		return parent::get_icon($template_id);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {
		
		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_EMBED_MODAL:

				// Since we're in a modal, make the embedPreview get reloaded when opening the modal
				$this->merge_pagesection_jsmethod_att(GD_TEMPLATE_LAYOUT_EMBEDPREVIEW, $blockgroup_block_atts, array('modalReloadEmbedPreview'));
				$this->add_att(GD_TEMPLATE_LAYOUT_EMBEDPREVIEW, $blockgroup_block_atts, 'template-cb', true);
				$this->append_att(GD_TEMPLATE_LAYOUT_EMBEDPREVIEW, $blockgroup_block_atts, 'class', GD_TEMPLATE_LAYOUT_EMBEDPREVIEW.' pop-merge');
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ShareModalBlockGroups();