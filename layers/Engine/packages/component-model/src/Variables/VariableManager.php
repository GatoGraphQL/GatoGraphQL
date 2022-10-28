<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Variables;

use PoP\ComponentModel\StaticHelpers\MethodHelpers;
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

        /**
         * Watch out! GraphiQL also uses the "variables" URL param,
         * but as a string. Hence, check if this param is an array,
         * and only then process it.
         */
        $variables = array_merge(
            App::getRequest()->query->all(),
            App::getRequest()->request->all(),
            App::getRequest()->query->has('variables') && is_array(App::getRequest()->query->all()['variables']) ? App::getRequest()->query->all()['variables'] : [],
            App::getRequest()->request->has('variables') && is_array(App::getRequest()->request->all()['variables']) ? App::getRequest()->request->all()['variables'] : []
        );

        /**
         * Convert associative arrays to stdClass, which is the
         * data structure used for inputs in GraphQL.
         *
         * Using associative array would not work, as ScalarTypeResolvers
         * can't receive an `array` as input to function `coerceValue`,
         * then `JSONObjectScalar` can only receive `stdClass`, not an array.
         */
        foreach ($variables as $variableName => $variableValue) {
            $isAssociativeArray = is_array($variableValue) && !array_is_list($variableValue);
            if (!$isAssociativeArray) {
                continue;
            }
            /** @var mixed[] $isAssociativeArray */
            $variables[$variableName] = MethodHelpers::associativeArrayToObject($variableValue);
        }

        return $variables;
    }
}
