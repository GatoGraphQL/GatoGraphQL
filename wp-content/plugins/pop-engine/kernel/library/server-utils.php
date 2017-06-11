<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Core (reusable) functions of the website
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_ServerUtils {

	// Counter: cannot start with a number, or the id will get confused
	// First number is 10, that is "a" in base 36
	protected static $templatedefinition_counter = 10;

	public static function is_not_mangled() {

		// By default, it is mangled, if not mangled then param "mangled" must have value "none"
		// Coment Leo 13/01/2017: get_vars() can't function properly since it references objects which have not been initialized yet,
		// when called at the very beginning. So then access the request directly
		return isset($_REQUEST[GD_URLPARAM_MANGLED]) && $_REQUEST[GD_URLPARAM_MANGLED] == GD_URLPARAM_MANGLED_NONE;
	}

	public static function is_mangled() {

		return !self::is_not_mangled();
	}
	
	/**
	 * Function used to create a definition for a template. Needed for reducing the filesize of the html generated for PROD
	 * Instead of using the name of the $template_id, we use a unique number in base 36, so the name will occupy much lower size
	 */
	public static function get_template_definition($template_id, $mirror = false) {

		// If not mangled, then that's it, use the original $template_id
		if (self::is_not_mangled()) {
			return $template_id;
		}

		// Mirror: it simply returns the $template_id again. It confirms in the code that this decision is deliberate 
		// (not calling function get_template_definition could also be that the developer forgot about it)
		// It is simply used to explicitly say that we need the same name as the template_id, eg: for the filtercomponents,
		// so that in the URL params it shows names that make sense (author=...&search=...)
		if ($mirror) {
			return $template_id;
		}

		$templatedefinition_type = self::get_templatedefinition_type();

		// Type 0: Use $template_id as the definition
		if ($templatedefinition_type === 0) {
			return $template_id;
		}

		// Comment Leo: Fix here! The array should be injectable, each plug-in should add its reserved names
		// Reserved definitions: those which are used with the mirroring, and which can conflict with a generated definition
		// Eg: define ('GD_TEMPLATE_FORMCOMPONENT_COMMENTID', PoP_ServerUtils::get_template_definition('cid', true));
		$reserved = array(
			'pid', // post id
			'uid', // user id
			'lid', // location id
			'cid', // comment id
			'fa', // to avoid template with class "fa"
			'btn', // to avoid template with class "btn"
			// 'tag', // for the pageSection; commented since renaming it to tagpage because of problems with prettify
		);
		do {

			// Convert the number to base 36 to save chars
			$counter = base_convert(self::$templatedefinition_counter, 10, 36);

			// Increase the counter by 1.
			self::$templatedefinition_counter++;

			// If we reach a number whose base 36 conversion starts with a number, and not a letter, then skip
			if (self::$templatedefinition_counter == 36) {

				// 36 in base 10 = 10 in base 36
				// 360 in base 10 = a0 in base 36
				self::$templatedefinition_counter = 360;
			}
			elseif (self::$templatedefinition_counter == 1296) {

				// 1296 in base 10 = 100 in base 36
				// 12960 in base 10 = a00 in base 36
				self::$templatedefinition_counter = 12960;
			}
			elseif (self::$templatedefinition_counter == 46656) {

				// 46656 in base 10 = 1000 in base 36
				// 466560 in base 10 = a000 in base 36
				self::$templatedefinition_counter = 466560;
			}
		} 
		while (in_array($counter, $reserved));

		// Type 2: Use base36 $counter as the definition
		if ($templatedefinition_type === 2) {
			return $counter;
		}

		// Type 1: Use both base36 counter and $template_id as the definition
		// Do not add "-" or "_" to the definition, since some templates cannot support it.
		// Eg: formcomponenteditor, used with wp_editor
		return $counter.$template_id;
	}

	/**
	 * Types:
	 * 0: Use $template_id as the definition
	 * 1: Use both base36 counter and $template_id as the definition
	 * 2: Use base36 counter as the definition
	 */
	public static function get_templatedefinition_type() {

		if (defined('POP_SERVER_TEMPLATEDEFINITION_TYPE')) {
			return POP_SERVER_TEMPLATEDEFINITION_TYPE;
		}

		return 0;
	}

	/**
	 * Use 'modules' or 'm' in the JS context. Used to compress the file size in PROD
	 */
	public static function compact_js_keys() {

		// Do not compact if not mangled
		if (self::is_not_mangled()) {

			return false;
		}

		if (defined('POP_SERVER_COMPACTJSKEYS')) {
			return POP_SERVER_COMPACTJSKEYS;
		}

		return false;
	}

	public static function use_cache() {

		if (defined('POP_SERVER_USECACHE')) {
			return POP_SERVER_USECACHE;
		}

		return false;
	}
}