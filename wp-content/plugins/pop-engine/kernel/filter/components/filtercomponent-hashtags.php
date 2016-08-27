<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Filter Base Typeahead
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_FilterComponent_BaseHashtags extends GD_FilterComponent {
	
	function get_tags() {

		$tags = array();

		// The tags might have the '#' symbol, if so remove it
		if ($value = $this->get_filterformcomponent_value()) {

			// tags provided separated by space, color or comma
			foreach (multiexplode(array(',', ';', ' '), $value) as $hashtag) {

				if ($hashtag) {
					
					$tags[] = (substr($hashtag, 0, 1) == '#') ? substr($hashtag, 1) : $hashtag;
				}
			}
		}

		return $tags;
	}	
	
}
