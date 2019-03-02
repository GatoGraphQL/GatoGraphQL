<?php
namespace PoP\Engine;

trait FilterQueryDataloaderTrait
{
    public function filterQuery($query, $data_properties)
    {
        global $filter_manager;
        return $filter_manager->filterQuery($query, $data_properties);
    }

    public function clearFilter()
    {
        global $filter_manager;
        $filter_manager->clearFilter();
    }
}
