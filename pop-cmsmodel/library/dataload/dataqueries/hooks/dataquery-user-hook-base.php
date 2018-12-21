<?php
namespace PoP\CMSModel;
 
abstract class DataQuery_UserHookBase extends \PoP\Engine\DataQuery_HookBase {

	function get_dataquery_name() {

		return GD_DATAQUERY_USER;
	}
}