<?php

declare(strict_types=1);

namespace PoP\SiteBuilderAPI\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\StratumManagerFactory;

class ApplicationStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT,
            [$this, 'maybeAddComponent']
        );
        $this->hooksAPI->addAction(
            'ApplicationState:addVars',
            [$this, 'addVars'],
            10,
            1
        );
    }
    /**
     * @param array<array> $vars_in_array
     */
    public function addVars(array $vars_in_array): void
    {
        [&$vars] = $vars_in_array;

        $platformmanager = StratumManagerFactory::getInstance();
        $stratum = $platformmanager->getStratum();
        $strata = $platformmanager->getStrata($stratum);
        $stratum_isdefault = $platformmanager->isDefaultStratum();
        
        $vars['stratum'] = $stratum;
        $vars['strata'] = $strata;
        $vars['stratum-isdefault'] = $stratum_isdefault;
    }
    
    public function maybeAddComponent(array $components): array
    {
        $vars = ApplicationState::getVars();
        if ($stratum = $vars['stratum'] ?? null) {
            $components[] = $this->translationAPI->__('stratum:', 'component-model') . $stratum;
        }

        return $components;
    }
}
