<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Configuration;

use PoP\ConfigurationComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Configuration\Request as UpstreamRequest;

class Request extends UpstreamRequest
{
    /**
     * @return string[]
     */
    protected static function getAllDataOutputItems(): array
    {
        return array_merge(
            parent::getAllDataOutputItems(),
            [
                DataOutputItems::MODULESETTINGS,
            ]
        );
    }

    /**
     * @return string[]
     */
    protected static function getDefaultDataOutputItems(): array
    {
        $defaultDataOutputItems = parent::getDefaultDataOutputItems();
        // Replace the DatasetSettings with Settings
        array_splice(
            $defaultDataOutputItems,
            array_search(
                \PoP\ComponentModel\Constants\DataOutputItems::DATASET_MODULE_SETTINGS,
                $defaultDataOutputItems
            ),
            1,
            [
                DataOutputItems::MODULESETTINGS,
            ]
        );
        return $defaultDataOutputItems;
    }
}
