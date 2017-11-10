<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_HTMLTags_Utils {

	protected static $htmltag_attributes;
	
	function init() {
	
		// Allow to add attributes 'async' or 'defer' to the script tag
		// Taken from https://stackoverflow.com/questions/18944027/how-do-i-defer-or-async-this-wordpress-javascript-snippet-to-load-lastly-for-fas
		add_filter(
			'script_loader_tag', 
			array('PoP_HTMLTags_Utils', 'maybe_add_scripttag_attributes'), 
			PHP_INT_MAX, 
			3
		);
	}

	// Allow to add attributes 'async' or 'defer' to the script tag
	function maybe_add_scripttag_attributes($tag, $handle, $src) {

		// Initialize
		if (is_null(self::$htmltag_attributes)) {

			self::$htmltag_attributes = apply_filters(
				'PoP_HTMLTags_Utils:htmltag_attributes',
				array()
			);
		}
		
		if ($attributes = self::$htmltag_attributes[$handle]) {

			return str_replace(
				" src='${src}'>",
				" src='${src}' ".$attributes.">",
				$tag
			);
		}

		return $tag;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
PoP_HTMLTags_Utils::init();
