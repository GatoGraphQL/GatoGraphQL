<?php
namespace PoP\Engine\FileStorage;

abstract class FileObjectBase extends FileBase {

	public function delete() {

		FileJSONStorage_Factory::get_instance()->delete($this->get_filepath());
	}

	public function save($object) {

		FileJSONStorage_Factory::get_instance()->save($this->get_filepath(), $object);
	}

	public function get() {

		FileJSONStorage_Factory::get_instance()->get($this->get_filepath());
	}
}
