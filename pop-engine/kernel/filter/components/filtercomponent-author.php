<?php
namespace PoP\Engine;

abstract class FilterComponent_AuthorBase extends FilterComponentBase {
	
	function get_author($filter) {

		return $this->get_filterinput_value($filter);
	}	
	
}
