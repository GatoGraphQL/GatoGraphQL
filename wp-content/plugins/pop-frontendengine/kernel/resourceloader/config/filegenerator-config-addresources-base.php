<?php
class PoP_ResourceLoader_ConfigAddResourcesFileGeneratorBase extends PoP_ResourceLoader_ResourcesConfigFileGeneratorBase {

	// Generate multiple config files (one for each combination of hierarchy and format) instead of just one
	function generate() {

		// Insert the current file URL to the generated resources file
		$renderer = $this->get_renderer();
		$renderer_filereproductions = $renderer->get();

		// Add the version param to the URL
		$fileurl = add_query_arg('ver', pop_version(), $this->get_fileurl());
		foreach ($renderer_filereproductions as $filereproduction) {

			$filereproduction->setFileURL($fileurl);
		}

		parent::generate();
	}
}
