<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class VotingProcessors_EM_Processor_Hooks {

	function __construct() {

		add_filter(
			'GD_EM_Template_Processor_CustomCarouselInners:layout', 
			array($this, 'carousel_layout')
		);
		add_filter(
			'GD_EM_Template_Processor_CustomCarouselInners:grid', 
			array($this, 'carousel_grid')
		);
	}

	function carousel_layout($layout) {

		return GD_TEMPLATE_LAYOUT_PREVIEWPOST_EVENT_DETAILS;
	}

	function carousel_grid($grid) {

		return array(
			'row-items' => 1, 
			'class' => 'col-sm-12',
			'divider' => 2,
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_EM_Processor_Hooks();
