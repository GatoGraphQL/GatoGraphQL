<?php
class PoP_Engine_FileLocationBase extends PoP_Engine_FileBase {

	function get_url() {

		return '';
	}

	function get_fileurl() {

		return $this->get_url().'/'.$this->get_filename();
	}
}
