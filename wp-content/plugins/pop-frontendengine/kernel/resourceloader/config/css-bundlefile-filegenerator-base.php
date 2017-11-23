<?php
class PoP_ResourceLoader_CSSBundleFileFileGeneratorBase extends PoP_ResourceLoader_BundleFileFileGeneratorBase {

	function get_filename() {

		return $this->filename.$this->extension;
	}

	function generate() {

		// Insert the current file URL to the generated resources file
		$renderer = $this->get_renderer();
		$renderer_filereproductions = $renderer->get();

		global $pop_resourceloaderprocessor_manager;
		$resources = $pop_resourceloaderprocessor_manager->filter_can_bundle($this->resources);

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