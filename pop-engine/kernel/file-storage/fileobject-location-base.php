<?php
class PoP_Engine_FileObjectBase extends PoP_Engine_FileBase {

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
