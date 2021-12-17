<?php

declare(strict_types=1);

namespace PoP\Application\Hooks;

use PoP\Application\Constants\Actions;
use PoP\Application\ModuleFilters\Lazy;
use PoP\Application\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Constants\Targets;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ModuleFiltering\ModuleFilterManager;
use PoP\BasicService\AbstractHookSet;

class LazyLoadHookSet extends AbstractHookSet
{
    private ?RequestHelperServiceInterface $requestHelperService = null;
    private ?Lazy $lazy = null;

    final public function setRequestHelperService(RequestHelperServiceInterface $requestHelperService): void
    {
        $this->requestHelperService = $requestHelperService;
    }
    final protected function getRequestHelperService(): RequestHelperServiceInterface
    {
        return $this->requestHelperService ??= $this->instanceManager->getInstance(RequestHelperServiceInterface::class);
    }
    final public function setLazy(Lazy $lazy): void
    {
        $this->lazy = $lazy;
    }
    final protected function getLazy(): Lazy
    {
        return $this->lazy ??= $this->instanceManager->getInstance(Lazy::class);
    }

    protected function init(): void
    {
        $this->getHooksAPI()->addAction(
            '\PoP\ComponentModel\Engine:getModuleData:start',
            array($this, 'start'),
            10,
            4
        );
        $this->getHooksAPI()->addAction(
            '\PoP\ComponentModel\Engine:getModuleData:dataloading-module',
            array($this, 'calculateDataloadingModuleData'),
            10,
            8
        );
        $this->getHooksAPI()->addAction(
            '\PoP\ComponentModel\Engine:getModuleData:end',
            array($this, 'end'),
            10,
            5
        );
    }

    public function start($root_module, $root_model_props_in_array, $root_props_in_array, $helperCalculations_in_array): void
    {
        $helperCalculations = &$helperCalculations_in_array[0];
        $helperCalculations['has-lazy-load'] = false;
    }

    public function calculateDataloadingModuleData(array $module, $module_props_in_array, $data_properties_in_array, $dataaccess_checkpoint_validation, $actionexecution_checkpoint_validation, $executed, $dbObjectIDOrIDs, $helperCalculations_in_array): void
    {
        $data_properties = &$data_properties_in_array[0];

        if ($data_properties[DataloadingConstants::LAZYLOAD] ?? null) {
            $helperCalculations = &$helperCalculations_in_array[0];
            $helperCalculations['has-lazy-load'] = true;
        }
    }

    public function end($root_module, $root_model_props_in_array, $root_props_in_array, $helperCalculations_in_array, $engine): void
    {
        $helperCalculations = &$helperCalculations_in_array[0];

        // Fetch the lazy-loaded data using the Background URL load
        if ($helperCalculations['has-lazy-load'] ?? null) {
            $url = GeneralUtils::addQueryArgs(
                [
                    Params::DATA_OUTPUT_ITEMS => [
                        DataOutputItems::META,
                        DataOutputItems::MODULE_DATA,
                        DataOutputItems::DATABASES,
                    ],
                    ModuleFilterManager::URLPARAM_MODULEFILTER => $this->getLazy()->getName(),
                    Params::ACTIONS . '[]' => Actions::LOADLAZY,
                ],
                $this->getRequestHelperService()->getCurrentURL()
            );
            $engine->addBackgroundUrl($url, array(Targets::MAIN));
        }
    }
}
