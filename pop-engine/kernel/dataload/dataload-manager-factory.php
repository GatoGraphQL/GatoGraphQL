<?php
namespace PoP\Engine;

class Dataloader_Manager_Factory {

	protected static $instance;

	public static function set_instance(Dataloader_Manager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?Dataloader_Manager {

		return self::$instance;
	}
}