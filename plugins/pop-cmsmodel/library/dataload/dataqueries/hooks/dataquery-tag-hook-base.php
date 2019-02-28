<?php
namespace PoP\CMSModel;

abstract class DataQuery_TagHookBase extends \PoP\Engine\DataQuery_HookBase
{
    public function getDataqueryName()
    {
        return GD_DATAQUERY_TAG;
    }
}
