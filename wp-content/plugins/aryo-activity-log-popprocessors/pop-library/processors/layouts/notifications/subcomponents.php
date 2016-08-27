<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SUBCOMPONENT_NOTIFICATIONCOMMENT', PoP_ServerUtils::get_template_definition('subcomponent-notificationcomment'));

class GD_Template_Processor_NotificationSubcomponentLayouts extends GD_Template_Processor_SubcomponentLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SUBCOMPONENT_NOTIFICATIONCOMMENT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_SUBCOMPONENT_NOTIFICATIONCOMMENT:
				
				$ret[] = GD_TEMPLATE_SCROLLLAYOUT_POSTCOMMENT;
				break;
		}

		return $ret;
	}

	function get_subcomponent_field($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_SUBCOMPONENT_NOTIFICATIONCOMMENT:

				return 'comment-object-id';
		}
	
		return parent::get_subcomponent_field($template_id);
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_NOTIFICATIONCOMMENT:

				return GD_DATALOADER_COMMENTLIST;
		}

		return parent::get_dataloader($template_id);
	}

	function is_individual($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_NOTIFICATIONCOMMENT:

				return false;
		}
	
		return parent::is_individual($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_SUBCOMPONENT_NOTIFICATIONCOMMENT:

				// Make the comment shine whenever added, similar to GD_TEMPLATE_LAYOUT_COMMENT_ADD
				// but without adding the scrollTop effect
				$this->append_att(GD_TEMPLATE_LAYOUT_COMMENT_LIST, $atts, 'class', 'pop-highlight');
			
				// Make it beep
				$this->merge_block_jsmethod_att(GD_TEMPLATE_LAYOUT_COMMENT_LIST, $atts, array('beep'));
				break;
		}

		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_NotificationSubcomponentLayouts();