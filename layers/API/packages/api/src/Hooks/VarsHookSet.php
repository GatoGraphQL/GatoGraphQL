<?php

declare(strict_types=1);

namespace PoP\API\Hooks;

use PoP\Root\App;
use PoP\API\Response\Schemes as APISchemes;
use PoP\ComponentModel\ModelInstance\ModelInstance;
use PoP\BasicService\AbstractHookSet;

class VarsHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            ModelInstance::HOOK_COMPONENTS_RESULT,
            array($this, 'getModelInstanceComponentsFromVars')
        );
    }

    public function getModelInstanceComponentsFromVars($components)
    {
        // Allow WP API to set the "routing-state" first
        // Each page is an independent configuration
        if (App::getState('scheme') === APISchemes::API) {
            $this->addFieldsToComponents($components);
        }

        // Namespaces change the configuration
        $components[] = $this->__('namespaced:', 'pop-engine') . (App::getState('namespace-types-and-interfaces') ?? false);

        return $components;
    }

    private function addFieldsToComponents(&$components): void
    {
        if ($fields = App::getState('query')) {
            // Serialize instead of implode, because $fields can contain $key => $value
            $components[] = $this->__('fields:', 'pop-engine') . serialize($fields);
        }
    }
}
