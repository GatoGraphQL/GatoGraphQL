<?php
class PoP_ResourceLoader_JSBundleFileFileGeneratorBase extends PoP_ResourceLoader_BundleFileFileGeneratorBase {

	protected $attribute;

	function set_filename($filename) {

		parent::set_filename($filename);

		// Reset the attribute
		$this->attribute = '';
	}

	function set_attribute($attribute) {

		$this->attribute = $attribute;
	}

	function get_filename() {

		return $this->filename.($this->attribute ? '-'.$this->attribute : '').$this->extension;
	}

	function generate() {

		// Insert the current file URL to the generated resources file
		$renderer = $this->get_renderer();
		$renderer_filereproductions = $renderer->get();

		global $pop_jsresourceloaderprocessor_manager;
		$resources = $pop_jsresourceloaderprocessor_manager->filter_can_bundle($this->resources);
		// Generate the following files:
		// 1. Normal, without defer or async scripts
		// 2. Defer
		// 3. Async

		$async_resources = $pop_jsresourceloaderprocessor_manager->filter_async($resources);
		$defer_resources = $pop_jsresourceloaderprocessor_manager->filter_defer($resources, $this->vars_hash_id);
		
		// Only valid for Progressive Booting...
		if (PoP_Frontend_ServerUtils::use_progressive_booting()) {

			// If these resources have been marked as 'noncritical', then defer loading them
			// $noncritical_resources = PoP_ResourceLoaderProcessorUtils::get_noncritical_resources($this->vars_hash_id);
			// global $pop_resourceloader_resourcecachemanager;
			// $noncritical_resources = $pop_resourceloader_resourcecachemanager->get_noncritical_resources($this->vars_hash_id);
			// $noncritical_resources = array();
			// if (/*!doing_post() && */PoP_ServerUtils::use_cache()) {
			global $gd_template_memorymanager;
			if ($noncritical_resources = $gd_template_memorymanager->get_cache($this->vars_hash_id, POP_MEMORYTYPE_NONCRITICALRESOURCES, true)) {
			// }
			// if ($noncritical_resources) {
				$defer_resources = array_values(array_unique(array_merge(
					$defer_resources,
					array_intersect($resources, $noncritical_resources)
				)));
			}
		}

		$normal_resources = array_values(array_diff(
			$resources,
			$async_resources,
			$defer_resources
		));
		if ($normal_resources) {
			$this->generate_item('', $normal_resources);
		}
		if ($async_resources) {
			$this->generate_item('async', $async_resources);
		}
		if ($defer_resources) {
			$this->generate_item('defer', $defer_resources);
		}
	}

	function generate_item($attribute, $resources) {

		$this->set_attribute($attribute);

		// Insert the current file URL to the generated resources file
		$renderer = $this->get_renderer();
		$renderer_filereproductions = $renderer->get();

		foreach ($renderer_filereproductions as $filereproduction) {

			// Set all the resources on the fileReproduction, so it can retrieve their contents
			$filereproduction->setResources($resources);
		}

		parent::generate();
	}
}