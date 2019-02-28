<?php
namespace PoP\Engine;

abstract class QueryDataDataloader extends Dataloader
{

    /**
     * Function to override
     */
    public function getDbobjectIds($data_properties)
    {
        return array();
    }
}
