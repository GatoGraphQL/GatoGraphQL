<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_BlocksBase extends PoPFrontend_Processor_BlocksBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_BLOCK;
	}

	function get_submenu($template_id) {

		return null;
	}

	function get_latestcount_template($template_id) {

		return null;
	}
	
	//-------------------------------------------------
	// PUBLIC Overriding Functions
	//-------------------------------------------------

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($messagefeedback = $this->get_messagefeedback($template_id)) {				
			$ret[] = $messagefeedback;
		}

		if ($controlgroup_top = $this->get_controlgroup_top($template_id)) {				
			$ret[] = $controlgroup_top;
		}
		if ($controlgroup_bottom = $this->get_controlgroup_bottom($template_id)) {				
			$ret[] = $controlgroup_bottom;
		}

		if ($submenu = $this->get_submenu($template_id)) {				
			$ret[] = $submenu;
		}

		if ($latestcount = $this->get_latestcount_template($template_id)) {				
			$ret[] = $latestcount;
		}
				
		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;
		
		if ($messagefeedback = $this->get_messagefeedback($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['messagefeedback'] = $gd_template_processor_manager->get_processor($messagefeedback)->get_settings_id($messagefeedback);

			$messagefeedback_pos = $this->get_messagefeedback_position($template_id);
			if ($messagefeedback_pos == 'top') {

				$ret['messagefeedback-top'] = true;
			}
			elseif ($messagefeedback_pos == 'bottom') {

				$ret['messagefeedback-bottom'] = true;
			}					
		}
		
		if ($show_controls = $this->get_att($template_id, $atts, 'show-controls')) {
			
			if ($controlgroup_top = $this->get_controlgroup_top($template_id)) {				
				
				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['controlgroup-top'] = $gd_template_processor_manager->get_processor($controlgroup_top)->get_settings_id($controlgroup_top);
			}
			if ($controlgroup_bottom = $this->get_controlgroup_bottom($template_id)) {				
				
				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['controlgroup-bottom'] = $gd_template_processor_manager->get_processor($controlgroup_bottom)->get_settings_id($controlgroup_bottom);
			}
		}

		// Only add the submenu if this is the main block! That way, both blockgroups and blocks can define the submenu, but only the main of them will show it
		if ($this->get_att($template_id, $atts, 'is-mainblock')) {
			
			if ($submenu = $this->get_submenu($template_id)) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['submenu'] = $gd_template_processor_manager->get_processor($submenu)->get_settings_id($submenu);
			}
		}

		if ($latestcount = $this->get_latestcount_template($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['latestcount'] = $gd_template_processor_manager->get_processor($latestcount)->get_settings_id($latestcount);
		}

		if ($this->add_clearfixdiv($template_id)) {

			$ret['add-clearfixdiv'] = true;
		}
		
		// if ($separator = $this->get_att($template_id, $atts, 'separator')) {
		// 	$ret['separator'] = $separator;
		// }
		if ($description = $this->get_att($template_id, $atts, 'description')) {
			$ret[GD_JS_DESCRIPTION/*'description'*/] = $description;
		}
		if ($description_bottom = $this->get_att($template_id, $atts, 'description-bottom')) {
			$ret['description-bottom'] = $description_bottom;
		}
		if ($description_top = $this->get_att($template_id, $atts, 'description-top')) {
			$ret['description-top'] = $description_top;
		}
		if ($description_abovetitle = $this->get_att($template_id, $atts, 'description-abovetitle')) {
			$ret['description-abovetitle'] = $description_abovetitle;
		}
		if ($title_htmltag = $this->get_att($template_id, $atts, 'title-htmltag')) {
			$ret['title-htmltag'] = $title_htmltag;
		}
		
		return $ret;
	}

	function get_template_crawlableitem($template_id, $atts) {

		$ret = parent::get_template_crawlableitem($template_id, $atts);

		$configuration = $this->get_template_configuration($template_id, $atts);

		// Important: add them in this order (top, center, bottom) because we might open an html tag
		// in top and close it in the bottom (eg: GETPOP_TEMPLATE_CODEBLOCK_OFFLINEFIRST)
		if ($description_abovetitle = $configuration['description-abovetitle']) {
			$ret[] = $description_abovetitle;
		}
		if ($description_top = $configuration['description-top']) {
			$ret[] = $description_top;
		}
		if ($description = $configuration[GD_JS_DESCRIPTION]) {
			$ret[] = $description;
		}
		if ($description_bottom = $configuration['description-bottom']) {
			$ret[] = $description_bottom;
		}
	
		return $ret;
	}	

	function get_template_runtimecrawlableitem($template_id, $atts) {

		$ret = parent::get_template_runtimecrawlableitem($template_id, $atts);

		// Add once again the Block Title (like in the parent), but also adding the corresponding html-tag
		if ($title = $this->get_block_title($template_id, $atts)) {
			
			$title_htmltag = $this->get_att($template_id, $atts, 'title-htmltag');
			$title = sprintf(
				'<%1$s>%2$s</%1$s>',
				$title_htmltag,
				$title
			);

			if ($this->get_att($template_id, $atts, 'add-titlelink')) {

				$title_link = $this->get_title_link($template_id);
			}

			if ($title_link) {
				$ret[] = sprintf(
					'<a href="%s">%s</a>',
					$title_link,
					$title
				);
			}
			else {
				$ret[] = $title;
			}
		}
	
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		// By default, every block is a main block (this can be overriden by the BlockGroup)
		$this->add_att($template_id, $atts, 'is-mainblock', true);

		$this->add_att($template_id, $atts, 'show-controls', true);

		if ($description = $this->get_description($template_id, $atts)) {
			$this->add_att($template_id, $atts, 'description', $description);
		}
		if ($description_bottom = $this->get_description_bottom($template_id, $atts)) {
			$this->add_att($template_id, $atts, 'description-bottom', $description_bottom);
		}
		if ($description_top = $this->get_description_top($template_id, $atts)) {
			$this->add_att($template_id, $atts, 'description-top', $description_top);
		}
		if ($description_abovetitle = $this->get_description_abovetitle($template_id, $atts)) {
			$this->add_att($template_id, $atts, 'description-abovetitle', $description_abovetitle);
		}
		if ($title_htmltag = $this->get_title_htmltag($template_id, $atts)) {
			$this->add_att($template_id, $atts, 'title-htmltag', $title_htmltag);
		}

		// Block target for the controls. This is set in advance by the blockgroup (panelbootstrapjavascript-base) or,
		// whenever the page access the block directly (eg: opening OpinionatedVoted in the quickview) then here
		$blocktarget = '#'.$atts['block-id'];
		if ($controlgroup_top = $this->get_controlgroup_top($template_id)) {
			$this->add_att($controlgroup_top, $atts, 'block-target', $blocktarget);
		}
		if ($controlgroup_bottom = $this->get_controlgroup_bottom($template_id)) {
			$this->add_att($controlgroup_bottom, $atts, 'block-target', $blocktarget);
		}
		
		// Allow Skeleton Screens: if setting $att 'use-skeletonscreen' then do not validate if the content is loaded
		// Then, the content will be loaded nevertheless, and this content will be used for the skeleton screen effect,
		// simply adding some extra styles together with style '.pop-block.pop-loadingcontent'
		if ($this->queries_external_domain($template_id, $atts)) {
			
			// If proxy => Content not loaded => Make it use the Skeleton screen
			$this->add_att($template_id, $atts, 'use-skeletonscreen', true);

			// Inform pop-engine to bring the data anyway, needed for the Skeleton Screen effect
			$this->add_att($template_id, $atts, 'validate-content-loaded', false);
		}

		$this->add_att($template_id, $atts, 'use-skeletonscreen', false);
		if ($this->get_att($template_id, $atts, 'use-skeletonscreen')) {

			// Add extra class to the block
			$this->append_att($template_id, $atts, 'class', 'pop-skeletonscreen');
		}

		return parent::init_atts($template_id, $atts);
	}

	//-------------------------------------------------
	// PROTECTED Functions
	//-------------------------------------------------

	protected function get_messagefeedback($template_id) {

		return null;
	}

	protected function get_controlgroup_top($template_id) {

		return null;
	}
	protected function get_controlgroup_bottom($template_id) {

		return null;
	}
	protected function get_messagefeedback_position($template_id) {
	
		return 'top';
	}
	protected function add_clearfixdiv($template_id) {
	
		return true;
	}
	protected function get_description($template_id, $atts) {
	
		return null;
	}
	protected function get_description_bottom($template_id, $atts) {
	
		return null;
	}
	protected function get_description_top($template_id, $atts) {
	
		return null;
	}
	protected function get_description_abovetitle($template_id, $atts) {
	
		return null;
	}
	protected function get_title_htmltag($template_id, $atts) {
	
		return 'h1';
	}

	protected function get_blocksections_classes($template_id) {
	
		return array_merge(
			parent::get_blocksections_classes($template_id),
			array(
				'controlgroup-top' => 'right pull-right',
				'controlgroup-bottom' => 'bottom pull-right',
			)
		);
	}
}
