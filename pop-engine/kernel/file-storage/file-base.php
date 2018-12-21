<?php
namespace PoP\Engine\FileStorage;

abstract class FileBase {

	function get_dir() {

		return '';
	}

	function get_filename() {

		return '';
	}

	function get_filepath() {

		return $this->get_dir().'/'.$this->get_filename();
	}

	function file_exists() {

		return file_exists($this->get_filepath());
	}
}
