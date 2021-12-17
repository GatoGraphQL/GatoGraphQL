<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI\Hooks;

use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\StratumManagerFactory;
use PoP\ConfigurationComponentModel\Constants\Stratum;
use PoP\BasicService\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        // Execute early, since others (eg: SPA) will be based on these updated values
        $this->getHooksAPI()->addAction(
            'ApplicationState:addVars',
            array($this, 'addVars'),
            5,
            1
        );
    }

    /**
     * Override values for the API mode!
     * Whenever doing ?scheme=api, the specific configuration below must be set in the vars
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;
        if (isset($vars['scheme']) && $vars['scheme'] == APISchemes::API) {
            // Only the data stratum is needed
            $platformmanager = StratumManagerFactory::getInstance();
            $vars['stratum'] = Stratum::DATA;
            $vars['strata'] = $platformmanager->getStrata($vars['stratum']);
            $vars['stratum-isdefault'] = $vars['stratum'] == $platformmanager->getDefaultStratum();
        }
    }
}
