<?php

declare(strict_types=1);

namespace PoPAPI\API\Hooks;

use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPAPI\API\Response\Schemes as APISchemes;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            $this->getModelInstanceComponentsFromAppState(...)
        );
    }

    public function getModelInstanceComponentsFromAppState($components)
    {
        // Allow WP API to set the "routing-state" first
        // Each page is an independent configuration
        if (App::getState('scheme') === APISchemes::API) {
            $query = App::getState('query');
            if ($query !== null) {
                $components[] = $this->__('query:', 'pop-engine') . $query;
            }
        }

        // Namespaces change the configuration
        $components[] = $this->__('namespaced:', 'pop-engine') . (App::getState('namespace-types-and-interfaces') ?? false);

        return $components;
    }
}
