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
        if ($this->variablesFromRequestCache === null) {
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
         * Obtain variables from $_POST and $_GET.
         */
        $variables = array_merge(
            App::getRequest()->query->all(),
            App::getRequest()->request->all()
        );

        /**
         * Using associative array would not work, as ScalarTypeResolvers
         * can't receive an `array` as input to function `coerceValue`,
         * then `JSONObjectScalar` can only receive `stdClass`, not an array.
         */
        $variables = $this->recursivelyConvertVariableEntriesFromArrayToObject($variables);

        return $variables;
    }

    /**
     * Convert associative arrays to objects in the
     * variables JSON entries, recursively.
     *
     * stdClass is the data structure used for inputs in GraphQL
     * 
     * For instance, storing this JSON:
     * 
     *   {
     *     "languageMapping": {
     *       "nb": "no"
     *     }
     *   }
     * 
     * ...must be interpreted as object, not array.
     *
     * @param array<string,mixed> $variables
     * @return array<string,mixed>
     */
    public function recursivelyConvertVariableEntriesFromArrayToObject(array $variables): array
    {
        foreach ($variables as $variableName => $variableValue) {
            if (!is_array($variableValue)) {
                continue;
            }
            /** @var mixed[] $variableValue */
            $variables[$variableName] = MethodHelpers::recursivelyConvertAssociativeArrayToStdClass($variableValue);
        }

        return $variables;
    }
}
