<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Variables;

use PoP\Root\App;
use PoP\Root\Module as RootModule;
use PoP\Root\ModuleConfiguration as RootModuleConfiguration;

class VariableManager implements VariableManagerInterface
{
    /**
     * Cache vars to take from the request
     *
     * @var array<string,mixed>|null
     */
    private ?array $variablesFromRequestCache = null;

    /**
     * @return array<string,mixed>
     */
    public function getVariablesFromRequest(): array
    {
        if (is_null($this->variablesFromRequestCache)) {
            $this->variablesFromRequestCache = $this->doGetVariablesFromRequest();
        }
        return $this->variablesFromRequestCache;
    }

    /**
     * @return array<string,mixed>
     */
    protected function doGetVariablesFromRequest(): array
    {
        /** @var RootModuleConfiguration */
        $rootModuleConfiguration = App::getModule(RootModule::class)->getConfiguration();
        if (!$rootModuleConfiguration->enablePassingStateViaRequest()) {
            return [];
        }

        // Watch out! GraphiQL also uses the "variables" URL param, but as a string
        // Hence, check if this param is an array, and only then process it
        return array_merge(
            App::getRequest()->query->all(),
            App::getRequest()->request->all(),
            App::getRequest()->query->has('variables') && is_array(App::getRequest()->query->all()['variables']) ? App::getRequest()->query->all()['variables'] : [],
            App::getRequest()->request->has('variables') && is_array(App::getRequest()->request->all()['variables']) ? App::getRequest()->request->all()['variables'] : []
        );
    }
}
