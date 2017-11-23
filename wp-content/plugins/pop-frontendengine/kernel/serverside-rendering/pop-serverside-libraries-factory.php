<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServerSide_Libraries_Factory {

	protected static $jslibrary, $jsruntime, $popmanager, $resourceloader;

	public static function set_jslibrary_instance($jslibrary) {
		self::$jslibrary = $jslibrary;
	}

	public static function get_jslibrary_instance() {

		return self::$jslibrary;
	}

	public static function set_jsruntime_instance($jsruntime) {
		self::$jsruntime = $jsruntime;
	}

	public static function get_jsruntime_instance() {

		return self::$jsruntime;
	}

	public static function set_popmanager_instance($popmanager) {
		self::$popmanager = $popmanager;
	}

	public static function get_popmanager_instance() {

		return self::$popmanager;
	}

	public static function set_resourceloader_instance($resourceloader) {
		self::$resourceloader = $resourceloader;
	}

	public static function get_resourceloader_instance() {

		return self::$resourceloader;
	}
}