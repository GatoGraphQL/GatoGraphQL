<?php
namespace PoP\Engine;

abstract class FilterComponent_Metaquery_UserBase extends FilterComponent_MetaqueryBase {
	
	function get_metaquery_key() {
	
		return MetaManager::get_meta_key($this->get_meta_key(), GD_META_TYPE_USER);
	}

	function get_meta_key() {
	
		return null;
	}
}
