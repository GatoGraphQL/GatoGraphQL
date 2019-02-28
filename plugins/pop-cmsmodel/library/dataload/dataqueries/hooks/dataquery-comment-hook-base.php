<?php
namespace PoP\CMSModel;

abstract class DataQuery_CommentHookBase extends \PoP\Engine\DataQuery_HookBase
{
    public function getDataqueryName()
    {
        return GD_DATAQUERY_COMMENT;
    }
}
