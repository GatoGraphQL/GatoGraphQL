<?php
namespace PoP\Engine;

abstract class FilterComponent_HashtagsBase extends FilterComponentBase {
	
	function get_tags($filter) {

		$tags = array();

		// The tags might have the '#' symbol, if so remove it
		if ($value = $this->get_filterinput_value($filter)) {

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
