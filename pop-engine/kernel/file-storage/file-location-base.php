<?php
namespace PoP\Engine\FileStorage;

abstract class FileLocationBase extends FileBase {

	function get_url() {

		return '';
	}

	function get_fileurl() {

		return $this->get_url().'/'.$this->get_filename();
	}
}
