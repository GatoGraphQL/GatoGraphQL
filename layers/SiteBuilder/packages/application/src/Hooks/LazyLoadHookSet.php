<?php

declare(strict_types=1);

namespace PoP\Application\Hooks;

use PoP\ComponentModel\Component\Component;
use PoP\Application\Constants\Actions;
use PoP\Application\ComponentFilters\Lazy;
use PoP\Application\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\Constants\DataOutputItems;
use PoP\ComponentModel\Constants\Params;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\HelperServices\RequestHelperServiceInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ConfigurationComponentModel\Constants\Targets;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

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
        App::addAction(
            '\PoP\ComponentModel\Engine:getComponentData:start',
            $this->start(...),
            10,
            4
        );
        App::addAction(
            '\PoP\ComponentModel\Engine:getComponentData:dataloading-component',
            $this->calculateDataloadingComponentData(...),
            10,
            8
        );
        App::addAction(
            '\PoP\ComponentModel\Engine:getComponentData:end',
            $this->end(...),
            10,
            5
        );
    }

    public function start(Component $root_component, array $root_model_props_in_array, array $root_props_in_array, array $helperCalculations_in_array): void
    {
        $helperCalculations = &$helperCalculations_in_array[0];
        $helperCalculations['has-lazy-load'] = false;
    }

    public function calculateDataloadingComponentData(Component $component, array $component_props_in_array, array $data_properties_in_array, ?FeedbackItemResolution $dataaccess_checkpoint_validation, ?FeedbackItemResolution $actionexecution_checkpoint_validation, ?array $executed, array $dbObjectIDOrIDs, array $helperCalculations_in_array): void
    {
        $data_properties = &$data_properties_in_array[0];

        if ($data_properties[DataloadingConstants::LAZYLOAD] ?? null) {
            $helperCalculations = &$helperCalculations_in_array[0];
            $helperCalculations['has-lazy-load'] = true;
        }
    }

    public function end(Component $root_component, array $root_model_props_in_array, array $root_props_in_array, array $helperCalculations_in_array, EngineInterface $engine): void
    {
        $helperCalculations = &$helperCalculations_in_array[0];

        // Fetch the lazy-loaded data using the Background URL load
        if ($helperCalculations['has-lazy-load'] ?? null) {
            $url = GeneralUtils::addQueryArgs(
                [
                    Params::DATA_OUTPUT_ITEMS => [
                        DataOutputItems::META,
                        DataOutputItems::COMPONENT_DATA,
                        DataOutputItems::DATABASES,
                    ],
                    Params::COMPONENTFILTER => $this->getLazy()->getName(),
                    Params::ACTIONS . '[]' => Actions::LOADLAZY,
                ],
                $this->getRequestHelperService()->getCurrentURL()
            );
            $engine->addBackgroundUrl($url, array(Targets::MAIN));
        }
    }
}
