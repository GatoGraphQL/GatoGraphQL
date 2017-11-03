<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCKGROUP', PoP_TemplateIDUtils::get_template_definition('buttoncontrol-reloadblockgroup'));
define ('GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCK', PoP_TemplateIDUtils::get_template_definition('buttoncontrol-reloadblock'));
define ('GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK', PoP_TemplateIDUtils::get_template_definition('buttoncontrol-loadlatestblock'));
define ('GD_TEMPLATE_BUTTONCONTROL_RESETEDITOR', PoP_TemplateIDUtils::get_template_definition('buttoncontrol-reseteditor'));
// define ('GD_TEMPLATE_BUTTONCONTROL_OPENALL', PoP_TemplateIDUtils::get_template_definition('buttoncontrol-openall'));

class GD_Template_Processor_ButtonControls extends GD_Template_Processor_ButtonControlsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_BUTTONCONTROL_FILTERTOGGLE,
			GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCKGROUP,
			GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCK,
			GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK,
			GD_TEMPLATE_BUTTONCONTROL_RESETEDITOR,
			// GD_TEMPLATE_BUTTONCONTROL_ADDCOMMENT,
			// GD_TEMPLATE_BUTTONCONTROL_OPENALL,
			// GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCK,
			// GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCKGROUP,
		);
	}
	function get_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCKGROUP:
			case GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCK:
			case GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK:
			case GD_TEMPLATE_BUTTONCONTROL_RESETEDITOR:

				return null;
		}

		return parent::get_text($template_id, $atts);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			// case GD_TEMPLATE_BUTTONCONTROL_FILTERTOGGLE:
			
			// 	return __('Search', 'pop-coreprocessors');

			case GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCKGROUP:
			case GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCK:
			case GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK:

				return __('Refresh', 'pop-coreprocessors');

			case GD_TEMPLATE_BUTTONCONTROL_RESETEDITOR:

				return __('Reset', 'pop-coreprocessors');

			// case GD_TEMPLATE_BUTTONCONTROL_ADDCOMMENT:

			// 	return __('Add comment', 'pop-coreprocessors');

			// case GD_TEMPLATE_BUTTONCONTROL_OPENALL:
				
			// 	return __('Open all', 'pop-coreprocessors');

			// case GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCK:
			// case GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCKGROUP:
				
			// 	return __('Show in Navigator', 'pop-coreprocessors');
		}

		return parent::get_label($template_id, $atts);
	}
	// function get_icon($template_id) {

	// 	switch ($template_id) {

	// 		// case GD_TEMPLATE_BUTTONCONTROL_FILTERTOGGLE:
			
	// 		// 	return 'glyphicon-search';

	// 		// case GD_TEMPLATE_BUTTONCONTROL_ADDCOMMENT:

	// 		// 	return 'glyphicon-plus';

	// 		case GD_TEMPLATE_BUTTONCONTROL_OPENALL:
				
	// 			return 'glyphicon-road';
	// 	}

	// 	return parent::get_icon($template_id);
	// }
	function get_fontawesome($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCKGROUP:
			case GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCK:
			case GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK:

				return 'fa-refresh';

			case GD_TEMPLATE_BUTTONCONTROL_RESETEDITOR:

				return 'fa-repeat';

			// case GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCK:
			// case GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCKGROUP:
				
			// 	return 'fa-folder-open-o';
		}

		return parent::get_fontawesome($template_id, $atts);
	}
	function get_btn_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BUTTONCONTROL_RESETEDITOR:
		
				return 'btn btn-compact btn-link';
		}

		return parent::get_btn_class($template_id, $atts);
	}
	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			// case GD_TEMPLATE_BUTTONCONTROL_FILTERTOGGLE:
			
			// 	$this->append_att($template_id, $atts, 'class', 'filter-toggle');
			// 	break;

			case GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCKGROUP:
			case GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCK:
			case GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK:

				$this->append_att($template_id, $atts, 'class', 'btn btn-compact btn-link');
				$this->merge_att($template_id, $atts, 'params', array(
					// 'data-target' => GD_URLPARAM_TARGET_PRINT,
					'data-blocktarget' => $this->get_att($template_id, $atts, 'block-target')
				));
				break;

			case GD_TEMPLATE_BUTTONCONTROL_RESETEDITOR:

				$this->append_att($template_id, $atts, 'class', 'pop-reset');
				break;

			// case GD_TEMPLATE_BUTTONCONTROL_ADDCOMMENT:

			// 	// $this->append_att($template_id, $atts, 'class', GD_AddComment_Utils::get_openmodal_link_class() . ' btn-success');
			// 	$this->append_att($template_id, $atts, 'class', ' btn-success');
			// 	// $this->merge_att($template_id, $atts, 'params', array_merge(
			// 	// 	array(
			// 	// 		'data-target' => GD_AddComment_Utils::get_openmodal_link_target($atts)
			// 	// 	),
			// 	// 	GD_AddComment_Utils::get_openmodal_link_params()
			// 	// ));
			// 	break;

			// case GD_TEMPLATE_BUTTONCONTROL_OPENALL:
				
			// 	$this->append_att($template_id, $atts, 'class', 'btn-primary');
			// 	break;

			// case GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCK:
			// case GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCKGROUP:
				
			// 	$this->append_att($template_id, $atts, 'class', 'btn-primary');
			// 	break;
		}

		// switch ($template_id) {

		// 	case GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK:

		// 		// Set the time for the click
		// 		$this->merge_att($template_id, $atts, 'params', array(
		// 			// Every 30 seconds
		// 			'data-clicktime' => 30000,
		// 		));
		// 		break;
		// }

		return parent::init_atts($template_id, $atts);
	}

	// function get_js_setting($template_id, $atts) {

	// 	$ret = parent::get_js_setting($template_id, $atts);

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK:

	// 			if ($target = $this->get_att($template_id, $atts, 'datasetcount-target')) {

	// 				$ret['datasetcount-target'] = $target;
	// 			}
	// 			break;
	// 	}

	// 	return $ret;
	// }

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {

			// case GD_TEMPLATE_BUTTONCONTROL_FILTERTOGGLE:
			// 	$this->add_jsmethod($ret, 'controlFilterToggle');
			// 	break;

			case GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCKGROUP:
				$this->add_jsmethod($ret, 'reloadBlockGroup');
				break;

			case GD_TEMPLATE_BUTTONCONTROL_RELOADBLOCK:
				$this->add_jsmethod($ret, 'reloadBlock');
				break;

			case GD_TEMPLATE_BUTTONCONTROL_LOADLATESTBLOCK:
				$this->add_jsmethod($ret, 'loadLatestBlock');
				// $this->add_jsmethod($ret, 'timeoutLoadLatestBlock');
				break;

			case GD_TEMPLATE_BUTTONCONTROL_RESETEDITOR:
				$this->add_jsmethod($ret, 'reset');
				break;

			// case GD_TEMPLATE_BUTTONCONTROL_OPENALL:
			// 	$this->add_jsmethod($ret, 'controlOpenAll');
			// 	break;

			// case GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCK:
			// 	$this->add_jsmethod($ret, 'controlBlockNavigate');
			// 	break;

			// case GD_TEMPLATE_BUTTONCONTROL_NAVIGATEBLOCKGROUP:
			// 	$this->add_jsmethod($ret, 'controlBlockGroupNavigate');
			// 	break;
		}
		return $ret;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ButtonControls();