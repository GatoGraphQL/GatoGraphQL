<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_WidgetMessagesBase extends GD_Template_Processor_MessagesBase {

	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', 'widgetmessage text-warning');
		return parent::init_atts($template_id, $atts);
	}
}
