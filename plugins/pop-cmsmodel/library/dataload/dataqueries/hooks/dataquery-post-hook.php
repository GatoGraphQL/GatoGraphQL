<?php
namespace PoP\CMSModel;

class DataQuery_PostHook extends DataQuery_PostHookBase
{
    public function getNocachefields()
    {
        return array('comments-count');
    }
}

/**
 * Initialization
 */
new DataQuery_PostHook();
