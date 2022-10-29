<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\WebserverRequests;

use PoP\Root\Exception\ShouldNotHappenException;

/**
 * Test that enabling/disabling a module works well.
 */
abstract class AbstractCodeEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest extends AbstractEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTest
{
    /**
     * @return array<string,array<string,mixed>> An array of [$moduleName => ['query' => "...", 'response-enabled' => "...", 'response-disabled' => "..."]]
     */
    protected function getModuleNameEntries(): array
    {
        $moduleEntries = [];
        foreach ($this->getModuleNames() as $moduleName) {
            $moduleEntries[$moduleName] = [
                'query' => $this->getModuleGraphQLQuery($moduleName),
                'response-enabled' => $this->getModuleEnabledExpectedGraphQLResponse($moduleName),
                'response-disabled' => $this->getModuleDisabledExpectedGraphQLResponse($moduleName),
            ];
        }
        return $moduleEntries;
    }

    /**
     * @return string[]
     */
    abstract protected function getModuleNames(): array;

    protected function getModuleGraphQLQuery(string $moduleName): string
    {
        $this->throwUnsupportedModuleName($moduleName);
    }

    protected function getModuleEnabledExpectedGraphQLResponse(string $moduleName): string
    {
        $this->throwUnsupportedModuleName($moduleName);
    }

    protected function getModuleDisabledExpectedGraphQLResponse(string $moduleName): string
    {
        $this->throwUnsupportedModuleName($moduleName);
    }

    protected function throwUnsupportedModuleName(string $moduleName): void
    {
        throw new ShouldNotHappenException(
            sprintf(
                'Configuration for module "%s" is not complete',
                $moduleName
            )
        );
    }
}
