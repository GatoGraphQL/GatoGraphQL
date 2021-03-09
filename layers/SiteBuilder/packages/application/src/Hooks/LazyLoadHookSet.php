<?php

declare(strict_types=1);

namespace PoP\Application\Hooks;

use PoP\Application\Constants\Actions;
use PoP\Application\ModuleFilters\Lazy;
use PoP\Application\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Constants\Targets;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManager;
use PoP\Hooks\AbstractHookSet;

class LazyLoadHookSet extends AbstractHookSet
{
    protected function init()
    {
        $this->hooksAPI->addAction(
            '\PoP\ComponentModel\Engine:getModuleData:start',
            array($this, 'start'),
            10,
            4
        );
        $this->hooksAPI->addAction(
            '\PoP\ComponentModel\Engine:getModuleData:dataloading-module',
            array($this, 'calculateDataloadingModuleData'),
            10,
            8
        );
        $this->hooksAPI->addAction(
            '\PoP\ComponentModel\Engine:getModuleData:end',
            array($this, 'end'),
            10,
            5
        );
    }

    public function start($root_module, $root_model_props_in_array, $root_props_in_array, $helperCalculations_in_array)
    {
        $helperCalculations = &$helperCalculations_in_array[0];
        $helperCalculations['has-lazy-load'] = false;
    }

    public function calculateDataloadingModuleData(array $module, $module_props_in_array, $data_properties_in_array, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs, $helperCalculations_in_array)
    {
        $data_properties = &$data_properties_in_array[0];

        if ($data_properties[DataloadingConstants::LAZYLOAD] ?? null) {
            $helperCalculations = &$helperCalculations_in_array[0];
            $helperCalculations['has-lazy-load'] = true;
        }
    }

    public function end($root_module, $root_model_props_in_array, $root_props_in_array, $helperCalculations_in_array, $engine)
    {
        $helperCalculations = &$helperCalculations_in_array[0];
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var Lazy */
        $lazy = $instanceManager->getInstance(Lazy::class);

        // Fetch the lazy-loaded data using the Background URL load
        if ($helperCalculations['has-lazy-load'] ?? null) {
            $url = GeneralUtils::addQueryArgs([
                Params::DATA_OUTPUT_ITEMS => [
                    DataOutputItems::META,
                    DataOutputItems::MODULE_DATA,
                    DataOutputItems::DATABASES,
                ],
                ModuleFilterManager::URLPARAM_MODULEFILTER => $lazy->getName(),
                Params::ACTIONS . '[]' => Actions::LOADLAZY,
            ], RequestUtils::getCurrentUrl());
            $engine->addBackgroundUrl($url, array(Targets::MAIN));
        }
    }
}
