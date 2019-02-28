<?php
namespace PoP\Engine;

trait FilterQueryDataloaderTrait
{
    public function filterQuery($query, $data_properties)
    {
        global $POP_FILTER_manager;
        return $POP_FILTER_manager->filterQuery($query, $data_properties);
    }

    public function clearFilter()
    {
        global $POP_FILTER_manager;
        $POP_FILTER_manager->clearFilter();
    }
}
