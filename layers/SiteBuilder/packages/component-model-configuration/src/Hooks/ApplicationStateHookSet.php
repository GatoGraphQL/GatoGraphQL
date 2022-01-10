<?php

declare(strict_types=1);

namespace PoP\ConfigurationComponentModel\Hooks;

use PoP\BasicService\AbstractHookSet;
use PoP\ConfigurationComponentModel\Constants\Targets;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ConfigurationComponentModel\Constants\Params;

class ApplicationStateHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTSFROMVARS_RESULT,
            [$this, 'maybeAddComponent']
        );
        $this->getHooksAPI()->addAction(
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

        // If not target, or invalid, reset it to "main"
        // We allow an empty target if none provided, so that we can generate the settings cache when no target is provided
        // (ie initial load) and when target is provided (ie loading pageSection)
        $target = strtolower($_REQUEST[Params::TARGET] ?? '');
        $targets = (array) $this->getHooksAPI()->applyFilters(
            'ApplicationState:targets',
            [
                Targets::MAIN,
            ]
        );
        if (!in_array($target, $targets)) {
            $target = Targets::MAIN;
        }

        $vars['target'] = $target;
    }

    public function maybeAddComponent(array $components): array
    {
        $vars = ApplicationState::getVars();
        if ($target = $vars['target'] ?? null) {
            $components[] = $this->__('target:', 'component-model') . $target;
        }

        return $components;
    }
}
