<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\State;

use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelComponentConfiguration;
use PoP\ConfigurationComponentModel\Configuration\EngineRequest;
use PoP\ConfigurationComponentModel\Constants\Params;
use PoP\ConfigurationComponentModel\Constants\Targets;
use PoP\ConfigurationComponentModel\Constants\Values;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootComponentConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    public function initialize(array &$state): void
    {
        // Override the settings from ComponentModel
        /** @var ComponentModelComponentConfiguration */
        $componentModelComponentConfiguration = App::getComponent(ComponentModelModule::class)->getConfiguration();
        $enableModifyingEngineBehaviorViaRequest = $componentModelComponentConfiguration->enableModifyingEngineBehaviorViaRequest();
        $state['dataoutputitems'] = EngineRequest::getDataOutputItems($enableModifyingEngineBehaviorViaRequest);

        /** @var RootComponentConfiguration */
        $rootComponentConfiguration = App::getComponent(RootModule::class)->getConfiguration();
        if ($rootComponentConfiguration->enablePassingStateViaRequest()) {
            // If not target, or invalid, reset it to "main"
            // We allow an empty target if none provided, so that we can generate the settings cache when no target is provided
            // (ie initial load) and when target is provided (ie loading pageSection)
            $target = strtolower(App::request(Params::TARGET) ?? App::query(Params::TARGET, ''));
            $targets = (array) App::applyFilters(
                'ApplicationState:targets',
                [
                    Targets::MAIN,
                ]
            );
            if (!in_array($target, $targets)) {
                $target = Targets::MAIN;
            }

            // If there is not format, then set it to 'default'
            // This is needed so that the /generate/ generated configurations under a $model_instance_id (based on the value of $state)
            // can match the same $model_instance_id when visiting that page
            $format = App::request(Params::FORMAT) ?? App::query(Params::FORMAT);
            $format = $format !== null ? strtolower($format) : Values::DEFAULT;

            $state['target'] = $target;
            $state['format'] = $format;
        } else {
            $state['target'] = Targets::MAIN;
            $state['format'] = Values::DEFAULT;
        }
    }
}
