<?php
namespace PoP\ConfigurationComponentModel;

use PoP\Hooks\Facades\HooksAPIFacade;

class VarsHooks
{
    public function __construct()
    {
        HooksAPIFacade::getInstance()->addFilter(
            'ApplicationState:dataoutputitems',
            [$this, 'getDataOutputItems'],
            0
        );
        HooksAPIFacade::getInstance()->addFilter(
            'ApplicationState:default-dataoutputitems',
            [$this, 'getDefaultDataOutputItems'],
            0
        );
    }

    public function getDataOutputItems($dataoutputitems)
    {
        $dataoutputitems[] = \PoP\ComponentModel\Constants\DataOutputItems::MODULESETTINGS;
        return $dataoutputitems;
    }

    public function getDefaultDataOutputItems($dataoutputitems)
    {
        // Replace the DatasetSettings with Settings
        array_splice(
            $dataoutputitems,
            array_search(
                \PoP\ComponentModel\Constants\DataOutputItems::DATASET_MODULE_SETTINGS,
                $dataoutputitems
            ),
            1,
            [
                \PoP\ComponentModel\Constants\DataOutputItems::MODULESETTINGS,
            ]
        );
        return $dataoutputitems;
    }
}

/**
 * Initialization
 */
new VarsHooks();
