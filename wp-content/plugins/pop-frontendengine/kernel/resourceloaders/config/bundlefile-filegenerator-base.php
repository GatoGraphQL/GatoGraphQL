<?php
class PoP_ResourceLoader_BundleFileFileGeneratorBase extends PoP_ResourceLoader_ResourcesFileGeneratorBase {

	protected $filename, $resources, $generated_referenced_files;

	function set_filename($filename) {

		$this->filename = $filename;
	}
	function get_filename() {

		return $this->filename;
	}
	function get_generated_referenced_files() {

		return $this->generated_referenced_files;
	}

	function set_resources($resources) {

		// Only the resources that can be bundled
		global $pop_resourceloaderprocessor_manager;
		$this->resources = $pop_resourceloaderprocessor_manager->filter_can_bundle($resources);
	}

	function get_resources() {

		return $this->resources ?? array();
	}

    function generate() {

		// Insert the current file URL to the generated resources file
		$renderer = $this->get_renderer();
		$renderer_filereproductions = $renderer->get();

		// Insert the current file URL to the generated resources file
		$renderer = $this->get_renderer();
		$renderer_filereproductions = $renderer->get();

		foreach ($renderer_filereproductions as $filereproduction) {

			// Set all the resources on the fileReproduction, so it can retrieve their contents
			$filereproduction->setResources($this->get_resources());
		}

		parent::generate();

		// Copy the depended-upon files, such as fonts referenced inside CSS files
		$this->copy_referenced_files();
	}

	protected function copy_referenced_files() {

		// Reset
		$this->generated_referenced_files = array();

		// Copy the dependencies, such as fonts referenced inside CSS files
		global $pop_resourceloaderprocessor_manager;
		$destination_dir = $this->get_dir();
		foreach ($this->get_resources() as $resource) {

			$processor = $pop_resourceloaderprocessor_manager->get_processor($resource);
			if ($resource_referenced_files = $processor->get_referenced_files($resource)) {

				$source_dir = $processor->get_dir($resource);
				foreach ($resource_referenced_files as $relative_path_to_referenced_file) {

					// Check that the file does not exist yet
					$destination_file = get_absolute_path($destination_dir.'/'.$relative_path_to_referenced_file);
					// if (!file_exists($destination_file)) {

					$source_file = get_absolute_path($source_dir.'/'.$relative_path_to_referenced_file);
					
					// Copy only works if the destination folder exists
					@mkdir(dirname($destination_file), 0777, true);
					copy($source_file, $destination_file);
					// }

					$this->generated_referenced_files[] = $destination_file;
				}
			}
		}
	}

	function get_renderer() {

        global $pop_resourceloader_mirrorcode_renderer;
        return $pop_resourceloader_mirrorcode_renderer;
    }
}