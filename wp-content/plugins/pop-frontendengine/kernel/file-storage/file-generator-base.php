<?php
class PoP_Frontend_FileGeneratorBase {

	function get_dir() {

		return '';
	}

	function get_url() {

		return '';
	}

	function get_filename() {

		return '';
	}

	function get_filepath(/*$filename*/) {

		return $this->get_dir().'/'.$this->get_filename();
	}

	function get_fileurl(/*$filename*/) {

		return $this->get_url().'/'.$this->get_filename();
	}

	// function get_file() {

	// 	return $this->get_filepath($this->get_filename());
	// }
}
