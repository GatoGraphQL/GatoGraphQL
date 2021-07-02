<?php

class PoP_ResourceLoader_NatureResourcesManagerFactory {

	protected static $instance;

	public static function setInstance(PoP_ResourceLoader_NatureResourcesManager $instance) {

		self::$instance = $instance;
	}

	public static function getInstance(): PoP_ResourceLoader_NatureResourcesManager {

		return self::$instance;
	}
}
