<?php
class PoP_Frontend_RendererFileGeneratorBase extends PoP_Frontend_FileGeneratorBase {

	function get_renderer() {

		return null;
	}

	function generate() {

		if ($renderer = $this->get_renderer()) {
			
			global $pop_frontend_filestorage;

			// // Create the directory structure
			// $this->create_dir();

			// Render and save the content
			$contents = $renderer->render();
			$pop_frontend_filestorage->save_file($this->get_filepath(), $contents);
		}
	}
}
