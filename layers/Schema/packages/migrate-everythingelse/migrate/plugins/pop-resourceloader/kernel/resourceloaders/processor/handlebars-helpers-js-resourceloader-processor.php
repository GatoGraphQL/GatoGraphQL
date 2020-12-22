<?php

class PoP_HandlebarsHelpersJSResourceLoaderProcessor extends PoP_JSResourceLoaderProcessor {

	function extractMapping(array $resource) {

		// No need to extract the mapping from this file (also, it doesn't exist under that getDir() folder)
		return false;
	}
}
