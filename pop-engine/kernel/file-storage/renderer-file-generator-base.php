<?php
namespace PoP\Engine\FileStorage;

abstract class RendererFileGeneratorBase extends FileLocationBase {

	function get_renderer() {

		return null;
	}

	function generate() {

		if ($renderer = $this->get_renderer()) {
			
			// Render and save the content
			$contents = $renderer->render();
			FileStorage_Factory::get_instance()->save_file($this->get_filepath(), $contents);
		}
	}
}
