<?php
namespace PoP\CMSModel;
 
abstract class DataQuery_TagHookBase extends \PoP\Engine\DataQuery_HookBase {

	function get_dataquery_name() {

		return GD_DATAQUERY_TAG;
	}
}