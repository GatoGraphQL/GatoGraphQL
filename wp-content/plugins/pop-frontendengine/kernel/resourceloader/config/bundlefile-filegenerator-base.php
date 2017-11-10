<?php
class PoP_ResourceLoader_BundleFileFileGeneratorBase extends PoP_ResourceLoader_ConfigFileGeneratorBase {

	private $filename, $attibute, $extension, $resources;

	protected function get_subfolder() {

		return PoP_Frontend_ServerUtils::bundle_external_files() ? 'global' : 'local';
	}

	function set_filename($filename) {

		$this->filename = $filename;

		// Reset the attribute
		$this->attribute = '';
	}

	function set_attribute($attribute) {

		$this->attribute = $attribute;
	}

	function set_extension($extension) {

		$this->extension = $extension;
	}

	function set_resources($resources) {

		$this->resources = $resources;
	}

	function get_filename() {

		return $this->filename.($this->attribute ? '-'.$this->attribute : '').$this->extension;
	}

	function get_renderer() {

        global $pop_resourceloader_mirrorcode_renderer;
        return $pop_resourceloader_mirrorcode_renderer;
    }

	function generate() {

		// Insert the current file URL to the generated resources file
		$renderer = $this->get_renderer();
		$renderer_filereproductions = $renderer->get();

		global $pop_resourceloaderprocessor_manager;
		$resources = $pop_resourceloaderprocessor_manager->filter_can_bundle($this->resources);

		// Generate the following files:
		// 1. Normal, without defer or async scripts
		// 2. Defer
		// 3. Async
		$async_resources = $pop_resourceloaderprocessor_manager->filter_async($resources);
		$defer_resources = $pop_resourceloaderprocessor_manager->filter_defer($resources);
		$normal_resources = array_diff(
			$resources,
			$async_resources,
			$defer_resources
		);
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