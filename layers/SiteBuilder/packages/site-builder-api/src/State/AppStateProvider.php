<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI\State;

use PoPAPI\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\StratumManagerFactory;
use PoP\ConfigurationComponentModel\Constants\Stratum;
use PoP\Root\State\AbstractAppStateProvider;

class AppStateProvider extends AbstractAppStateProvider
{
    public function initialize(array &$state): void
    {
        $platformmanager = StratumManagerFactory::getInstance();
        $stratum = $platformmanager->getStratum();
        $strata = $platformmanager->getStrata($stratum);
        $stratum_isdefault = $platformmanager->isDefaultStratum();

        $state['stratum'] = $stratum;
        $state['strata'] = $strata;
        $state['stratum-isdefault'] = $stratum_isdefault;
    }

    /**
     * Override values for the API mode!
     * Whenever doing ?scheme=api, the specific configuration below
     * must be set in the vars
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
        $state['stratum-isdefault'] = $state['stratum'] == $platformmanager->getDefaultStratum();
    }
}
