<?php

declare(strict_types=1);

namespace PoP\API\Hooks;

use PoP\Root\App;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\Root\Hooks\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromAppState')
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
