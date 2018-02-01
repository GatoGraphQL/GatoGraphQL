<?php
class PoP_Engine_RendererFileGeneratorBase extends PoP_Engine_FileLocationBase {

	function get_renderer() {

		return null;
	}

	// function skip_if_file_exists() {

	// 	return false;
	// }

	function generate() {

		// // Check if the file already exists, and we're told not to re-generate the file in that case
		// if ($this->skip_if_file_exists() && $this->file_exists()) {
			
		// 	return;
		// }

		if ($renderer = $this->get_renderer()) {
			
			global $pop_engine_filestorage;

			// Render and save the content
			$contents = $renderer->render();
			$pop_engine_filestorage->save_file($this->get_filepath(), $contents);
		}
	}
}
