<?php
class PoP_Engine_FileBase {

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
