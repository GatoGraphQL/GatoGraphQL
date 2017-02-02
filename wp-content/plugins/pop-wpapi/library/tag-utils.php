<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Mentions Utils
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoP_TagUtils {

	public static function show_tag_symbol() {

		return apply_filters('PoP_TagUtils:show_tag_symbol', true);
	}

	public static function get_tag_symbol() {

		$symbol = apply_filters('PoP_TagUtils:tag_symbol', '#');
		return self::show_tag_symbol() ? $symbol : '';
	}

	public static function get_tag_namedescription($tag, $add_symbol = false) {

		$value = ($add_symbol ? self::get_tag_symbol() : '').$tag->name;
					
		// If there's a description, then use it
		if ($tag->description) {

			$value = sprintf(
				__('%1$s (%2$s)', 'pop-wpapi'),
				$value,
				$tag->description
			);
		}

		return $value;
	}
}