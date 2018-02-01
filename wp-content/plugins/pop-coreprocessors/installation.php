<?php
class PoP_CoreProcessors_Installation {

	function system_generate() {

		global $popcore_userloggedinstyles_filegenerator;

		// User Logged-in CSS
		$popcore_userloggedinstyles_filegenerator->generate();
	}
}