<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\WebserverRequests;

use PoP\Root\Exception\ShouldNotHappenException;

/**
 * Test that enabling/disabling a module works well.
 */
abstract class AbstractCodeEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase
{
    /**
     * @return array<string,array<string,mixed>> An array of [$moduleName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."]]
     */
    protected static function getModuleNameEntries(): array
    {
        $moduleEntries = [];
        foreach (static::getModuleNames() as $moduleName) {
            $moduleEntries[$moduleName] = [
                'query' => static::getModuleGraphQLQuery($moduleName),
                'response-enabled' => static::getModuleEnabledExpectedGraphQLResponse($moduleName),
                'response-disabled' => static::getModuleDisabledExpectedGraphQLResponse($moduleName),
            ];
        }
        return $moduleEntries;
    }

    /**
     * @return string[]
     */
    abstract protected static function getModuleNames(): array;

    protected static function getModuleGraphQLQuery(string $moduleName): string
    {
        static::throwUnsupportedModuleName($moduleName);
    }

    protected static function getModuleEnabledExpectedGraphQLResponse(string $moduleName): string
    {
        static::throwUnsupportedModuleName($moduleName);
    }

    protected static function getModuleDisabledExpectedGraphQLResponse(string $moduleName): string
    {
        static::throwUnsupportedModuleName($moduleName);
    }

    protected static function throwUnsupportedModuleName(string $moduleName): never
    {
        throw new ShouldNotHappenException(
            sprintf(
                'Configuration for module "%s" is not complete',
                $moduleName
            )
        );
    }
}
