<?php
namespace PoP\CMSModel;

abstract class DataQuery_PostHookBase extends \PoP\Engine\DataQuery_HookBase
{
    public function getDataqueryName()
    {
        return GD_DATAQUERY_POST;
    }
}
