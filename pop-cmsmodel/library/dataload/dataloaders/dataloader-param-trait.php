<?php
namespace PoP\CMSModel;
 
trait Dataloader_ParamTrait {

	protected function get_param_name() {

		return '';
	}

	function get_dbobject_ids($data_properties) {
	
		// When editing a post in the frontend, set param "pid"
		if ($id = $_REQUEST[$this->get_param_name()]) {

			if (is_array($id)) {

				return \PoP\Engine\Utils::limit_results($id);
			}

			return array($id);
		}

		return array();
	}
}
