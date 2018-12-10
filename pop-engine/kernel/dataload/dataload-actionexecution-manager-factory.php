<?php

class GD_DataLoad_ActionExecution_Manager_Factory {

	protected static $instance;

	public static function set_instance(GD_DataLoad_ActionExecution_Manager $instance) {

		self::$instance = $instance;
	}

	public static function get_instance(): ?GD_DataLoad_ActionExecution_Manager {

		return self::$instance;
	}
}