<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

trait ModifyValueFixtureEndpointWebserverRequestTestCaseTrait
{
    protected function isOriginalTestCase(string $dataName): bool
    {
        return str_ends_with($dataName, ':0');
    }

    /**
     * Use the ending ":0" to denote the "before" test, i.e.
     * testing that the current value in the DB produces a certain
     * result
     */
    protected function executeSetUpTearDownUnlessIsOriginalTestCase(string $dataName): bool
    {
        return !$this->isOriginalTestCase($dataName);
    }

    /**
     * Execute the original test case first, just to make it
     * easier to understand the flow when running `phpunit`.
     *
     * The execution order now becomes:
     *
     *   1. fixtureName:0
     *   2. fixtureName
     *   .. fixtureName:anythingElse
     *
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected function reorderProviderEndpointEntriesToExecuteOriginalTestFirst(array $providerItems): array
    {
        $originalTestProviderItems = array_filter(
            $providerItems,
            fn (string $fixtureName) => $this->isOriginalTestCase($fixtureName),
            ARRAY_FILTER_USE_KEY
        );
        if ($originalTestProviderItems === []) {
            return $providerItems;
        }
        return array_merge(
            $originalTestProviderItems,
            array_diff_key(
                $providerItems,
                $originalTestProviderItems,
            )
        );
    }
}
