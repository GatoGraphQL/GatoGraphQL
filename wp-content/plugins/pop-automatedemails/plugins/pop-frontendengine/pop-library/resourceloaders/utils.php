<?php

class PoP_AutomatedEmails_Frontend_ResourceLoader_Utils {

	public static function get_automatedemail_pages() {

		return apply_filters(
			'PoP_AutomatedEmails_Frontend_ResourceLoader_Utils:automatedemail-pages',
			array()
		);
	}
}