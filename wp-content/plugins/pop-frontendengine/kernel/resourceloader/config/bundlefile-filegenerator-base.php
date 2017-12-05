<?php
class PoP_ResourceLoader_BundleFileFileGeneratorBase extends PoP_ResourceLoader_ConfigFileGeneratorBase {

	protected $filename, $extension, $resources, $vars_hash_id;

	function set_filename($filename) {

		$this->filename = $filename;
	}

	function set_extension($extension) {

		$this->extension = $extension;
	}

	function set_resources($resources) {

		$this->resources = $resources;
	}

	function set_vars_hash_id($vars_hash_id) {

		$this->vars_hash_id = $vars_hash_id;
	}

	function get_renderer() {

        global $pop_resourceloader_mirrorcode_renderer;
        return $pop_resourceloader_mirrorcode_renderer;
    }
}