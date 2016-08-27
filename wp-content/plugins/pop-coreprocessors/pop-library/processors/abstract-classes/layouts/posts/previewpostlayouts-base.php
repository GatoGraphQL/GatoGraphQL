<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CONSTANT_AUTHORPOSITION_ABOVETHUMB', 'abovethumb');
define ('GD_CONSTANT_AUTHORPOSITION_ABOVETITLE', 'abovetitle');
define ('GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT', 'belowcontent');

class GD_Template_Processor_PreviewPostLayoutsBase extends GD_Template_Processor_PreviewObjectLayoutsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_PREVIEWPOST;
	}
	
	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($belowthumb_templates = $this->get_belowthumb_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$belowthumb_templates
			);
		}
		if ($abovecontent_templates = $this->get_abovecontent_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$abovecontent_templates
			);
		}
		if ($belowcontent_templates = $this->get_belowcontent_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$belowcontent_templates
			);
		}
		if ($bottom_templates = $this->get_previewpost_bottom_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$bottom_templates
			);
		}
		if ($beforecontent_templates = $this->get_beforecontent_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$beforecontent_templates
			);
		}
		if ($aftercontent_templates = $this->get_aftercontent_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$aftercontent_templates
			);
		}
		if ($post_thumb = $this->get_post_thumb_template($template_id)) {
			$ret[] = $post_thumb;
		}
		if ($content_template = $this->get_content_template($template_id)) {
			$ret[] = $content_template;
		}

		return $ret;
	}

	// function get_subcomponent_modules($template_id, $atts) {
	function get_subcomponent_modules($template_id) {

		$ret = parent::get_subcomponent_modules($template_id);

		$modules = array();

		// Show author or not: if position defined
		if ($author_template = $this->get_author_template($template_id)) {

			$modules[] = $author_template;
		}

		// Show author avatar: only if no thumb template defined, and author avatar is defined
		if (!$this->get_post_thumb_template($template_id)) {

			if ($author_avatar = $this->get_author_avatar_template($template_id)) {

				$modules[] = $author_avatar;
			}
		}

		if ($modules) {

			$ret['authors'] = array(
				'modules' => $modules,
				'dataloader' => GD_DATALOADER_USERLIST
			);
		}

		return $ret;
	}

	// function show_content($template_id) {

	// 	return false;
	// }
	// function get_content_maxheight($template_id, $atts) {

	// 	return null;
	// }

	// function get_block_jsmethod($template_id, $atts) {

	// 	$ret = parent::get_block_jsmethod($template_id, $atts);

	// 	// if ($this->show_content($template_id)) {

	// 	// 	// Make the images inside img-responsive
	// 	// 	$this->add_jsmethod($ret, 'imageResponsive');
			
	// 	// 	// Add the popover for the @mentions
	// 	// 	$this->add_jsmethod($ret, 'contentPopover');
	// 	// }

	// 	// if ($this->get_content_maxlength($template_id, $atts)) {
		
	// 	// 	$this->add_jsmethod($ret, 'showmore', 'content');
	// 	// }

	// 	return $ret;
	// }

	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);

		$ret[] = 'cat-slugs';
		if ($this->show_posttitle($template_id)) {

			$ret[] = 'title';
		}
		if ($this->show_date($template_id)) {

			$ret[] = 'datetime';
		}
		// if ($this->show_content($template_id)) {
		// 	$ret[] = 'content';
		// }

		return $ret;
	}

	function show_posttitle($template_id) {

		return true;
	}
	function show_date($template_id) {

		return false;
	}

	function get_content_template($template_id) {

		return null;
	}
	function get_post_thumb_template($template_id) {

		return null;
	}
	function get_author_template($template_id) {

		return GD_TEMPLATE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR;
		// return GD_TEMPLATE_LAYOUTPOST_AUTHOR;
	}
	function get_author_avatar_template($template_id) {

		return null;
	}
	function get_title_beforeauthors($template_id, $atts) {

		return array();
	}
	function get_title_afterauthors($template_id, $atts) {

		return array();
	}
	function author_positions($template_id) {

		return array(
			GD_CONSTANT_AUTHORPOSITION_ABOVETHUMB
		);
	}
	// function layoutextra_position($template_id, $atts) {

	// 	return GD_CONSTANT_LAYOUTEXTRAPOSITION_BELOWTHUMB;
	// }
	function get_authors_separator($template_id, $atts) {

		return GD_CONSTANT_AUTHORS_SEPARATOR;
	}

	function get_belowthumb_layouts($template_id) {

		return array();
	}
	function get_abovecontent_layouts($template_id) {

		$ret = array();

		// if ($this->show_content($template_id)) {

		// 	// Add the layout below to preload the popover content for user @mentions, coupled with js function 'contentPopover'
		// 	$ret[] = GD_TEMPLATE_LAYOUT_POSTUSERMENTIONS;
		// }

		return $ret;
	}
	function get_belowcontent_layouts($template_id) {

		return array();
	}
	function get_bottom_layouts($template_id) {

		return array();
	}
	function get_aftercontent_layouts($template_id) {

		return array();
	}
	function get_beforecontent_layouts($template_id) {

		return array();
	}
	function get_previewpost_bottom_layouts($template_id) {

		// Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
		return apply_filters('GD_Template_Processor_PreviewPostLayoutsBase:bottom_layouts', $this->get_bottom_layouts($template_id), $template_id);
	}
	// function get_extra_class($template_id) {

	// 	return '';
	// }

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		// if ($this->show_content($template_id)) {
		// 	$ret['show-content'] = true;
		// 	// $ret['content-maxlength'] = $this->get_content_maxlength($template_id, $atts);
		// 	if ($height = $this->get_content_maxheight($template_id, $atts)) {
		// 		$ret['content-maxlength'] = $height;
		// 	}
		// }

		if ($belowthumb_templates = $this->get_belowthumb_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['belowthumb'] = $belowthumb_templates;
			foreach ($belowthumb_templates as $belowthumb_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$belowthumb_template] = $gd_template_processor_manager->get_processor($belowthumb_template)->get_settings_id($belowthumb_template);
			}
		}
		if ($abovecontent_templates = $this->get_abovecontent_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['abovecontent'] = $abovecontent_templates;
			foreach ($abovecontent_templates as $abovecontent_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$abovecontent_template] = $gd_template_processor_manager->get_processor($abovecontent_template)->get_settings_id($abovecontent_template);
			}
		}
		if ($belowcontent_templates = $this->get_belowcontent_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['belowcontent'] = $belowcontent_templates;
			foreach ($belowcontent_templates as $belowcontent_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$belowcontent_template] = $gd_template_processor_manager->get_processor($belowcontent_template)->get_settings_id($belowcontent_template);
			}
		}
		if ($bottom_templates = $this->get_previewpost_bottom_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['bottom'] = $bottom_templates;
			foreach ($bottom_templates as $bottom_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$bottom_template] = $gd_template_processor_manager->get_processor($bottom_template)->get_settings_id($bottom_template);
			}
		}
		if ($beforecontent_templates = $this->get_beforecontent_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['beforecontent'] = $beforecontent_templates;
			foreach ($beforecontent_templates as $beforecontent_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$beforecontent_template] = $gd_template_processor_manager->get_processor($beforecontent_template)->get_settings_id($beforecontent_template);
			}
		}
		if ($aftercontent_templates = $this->get_aftercontent_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['aftercontent'] = $aftercontent_templates;
			foreach ($aftercontent_templates as $aftercontent_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$aftercontent_template] = $gd_template_processor_manager->get_processor($aftercontent_template)->get_settings_id($aftercontent_template);
			}
		}
		if ($author_template = $this->get_author_template($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['authors'] = $gd_template_processor_manager->get_processor($author_template)->get_settings_id($author_template);
			$ret['authors-position'] = $this->author_positions($template_id);
			$ret['authors-sep'] = $this->get_authors_separator($template_id, $atts);
			if ($title_beforeauthors = $this->get_title_beforeauthors($template_id, $atts)) {

				$ret[GD_JS_TITLES/*'titles'*/]['beforeauthors'] = $title_beforeauthors;
			}
			if ($title_afterauthors = $this->get_title_afterauthors($template_id, $atts)) {

				$ret[GD_JS_TITLES/*'titles'*/]['afterauthors'] = $title_afterauthors;
			}
		}

		if ($this->show_posttitle($template_id)) {
			$ret['show-posttitle'] = true;
		}
		if ($this->show_date($template_id)) {
			$ret['show-date'] = true;
			$ret[GD_JS_TITLES/*'titles'*/]['date'] = __('Go to permalink', 'pop-coreprocessors');
			$ret[GD_JS_CLASSES/*'classes'*/]['date'] = 'close close-sm';
		}

		if ($content_template = $this->get_content_template($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['content'] = $gd_template_processor_manager->get_processor($content_template)->get_settings_id($content_template);
		}

		if ($post_thumb = $this->get_post_thumb_template($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['postthumb'] = $gd_template_processor_manager->get_processor($post_thumb)->get_settings_id($post_thumb);
		}
		else {

			if ($author_avatar = $this->get_author_avatar_template($template_id)) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['author-avatar'] = $gd_template_processor_manager->get_processor($author_avatar)->get_settings_id($author_avatar);
			}
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		if ($author_template = $this->get_author_template($template_id)) {
			
			$this->append_att($author_template, $atts, 'class', 'preview-author');
		}

		// // Hide the @mentions popover code
		// if (in_array(GD_TEMPLATE_LAYOUT_POSTUSERMENTIONS, $this->get_modules($template_id))) {
		// 	$this->append_att(GD_TEMPLATE_LAYOUT_POSTUSERMENTIONS, $atts, 'class', 'hidden');
		// }
			
		return parent::init_atts($template_id, $atts);
	}
}