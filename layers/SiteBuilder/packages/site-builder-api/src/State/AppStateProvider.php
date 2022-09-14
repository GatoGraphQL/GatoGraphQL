<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI\State;

use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\StratumManagerFactory;
use PoP\ConfigurationComponentModel\Constants\Stratum;
use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    /**
     * @param array<string,mixed> $state
     */
    public function initialize(array &$state): void
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if ($rootModuleConfiguration->enablePassingStateViaRequest()) {
            $platformmanager = StratumManagerFactory::getInstance();
            $stratum = $platformmanager->getStratum();
            $strata = $platformmanager->getStrata($stratum);
            $stratum_isdefault = $platformmanager->isDefaultStratum();

            $state['stratum'] = $stratum;
            $state['strata'] = $strata;
            $state['stratum-isdefault'] = $stratum_isdefault;
        } else {
            $state['stratum'] = null;
            $state['strata'] = null;
            $state['stratum-isdefault'] = null;
        }
    }

    /**
     * Override values for the API mode!
     * Whenever doing ?scheme=api, the specific configuration below
     * must be set in the vars
     * @param array<string,mixed> $state
     */
    public function consolidate(array &$state): void
    {
        if ($state['scheme'] !== APISchemes::API) {
            return;
        }
        // Only the data stratum is needed
        $platformmanager = StratumManagerFactory::getInstance();
        $state['stratum'] = Stratum::DATA;
        $state['strata'] = $platformmanager->getStrata($state['stratum']);
        $state['stratum-isdefault'] = $state['stratum'] === $platformmanager->getDefaultStratum();
    }
}
