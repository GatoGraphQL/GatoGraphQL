<?php
namespace PoP\Engine\FileStorage;

abstract class FileObjectBase extends FileBase {

	public function delete() {

		global $pop_engine_filejsonstorage;
		$pop_engine_filejsonstorage->delete($this->get_filepath());
	}

	public function save($object) {

		global $pop_engine_filejsonstorage;
		$pop_engine_filejsonstorage->save($this->get_filepath(), $object);
	}

	public function get() {

		global $pop_engine_filejsonstorage;
		return $pop_engine_filejsonstorage->get($this->get_filepath());
	}
}
