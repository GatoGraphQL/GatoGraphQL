<?php
class PoP_Engine_RendererFileGeneratorBase extends PoP_Engine_FileGeneratorBase {

	function get_renderer() {

		return null;
	}

	function generate() {

		if ($renderer = $this->get_renderer()) {
			
			global $pop_engine_filestorage;

			// Render and save the content
			$contents = $renderer->render();
			$pop_engine_filestorage->save_file($this->get_filepath(), $contents);
		}
	}
}
