<?php

declare(strict_types=1);

namespace PHPUnitForGraphQLAPI\GraphQLAPI\Integration;

trait ModifyValueFixtureEndpointWebserverRequestTestCaseTrait
{
    /**
     * Use the ending ":0" to denote the "before" test, i.e.
     * testing that the current value in the DB produces a certain
     * result
     */
    protected function executeSetUpTearDownUnlessIsOriginalTestCase(string $dataName): bool
    {
        return !str_ends_with($dataName, ':0');
    }
}
