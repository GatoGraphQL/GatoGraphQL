<?php
class PoP_ResourceLoader_BundleFileFileGeneratorBase extends PoP_ResourceLoader_ConfigFileGeneratorBase {

	protected $filename, $extension, $resources, $cachename;

	function set_filename($filename) {

		$this->filename = $filename;
	}

	function set_extension($extension) {

		$this->extension = $extension;
	}

	function set_resources($resources) {

		$this->resources = $resources;
	}

	function set_cachename($cachename) {

		$this->cachename = $cachename;
	}

	function get_renderer() {

        global $pop_resourceloader_mirrorcode_renderer;
        return $pop_resourceloader_mirrorcode_renderer;
    }
}