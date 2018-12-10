<?php

function pop_version() {

	return apply_filters('PoP:version', POP_ENGINE_VERSION);
}

function pop_loaded() {

	return defined('POP_STARTUP_INITIALIZED') && POP_STARTUP_INITIALIZED;
}