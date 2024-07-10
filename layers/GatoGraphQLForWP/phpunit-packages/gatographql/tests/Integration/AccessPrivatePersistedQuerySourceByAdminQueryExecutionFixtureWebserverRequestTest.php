<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AccessPrivatePersistedQuerySourceByAdminQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPrivatePersistedQueryQueryExecutionFixtureWebserverRequestTestCase
{
    /**
     * This folder doesn't actually matter, as the content will
     * be overridden anyway
     */
    protected static function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-private-persisted-queries-success';
    }

    protected static function viewSource(): bool
    {
        return true;
    }

    /**
     * This test disables the endpoint, then update the providerItem
     * via code.
     *
     * @param array<string,mixed> $providerItems
     * @return array<string,mixed>
     */
    protected static function customizeProviderEndpointEntries(array $providerItems): array
    {
        // expectedContentType
        $providerItems['private-persisted-query'][0] = 'text/html';
        /**
         * Expect to NOT find the "You are not authorized" message
         *
         * @see layers/GatoGraphQLForWP/plugins/gatographql/src/Services/Helpers/RenderingHelpers.php
         */
        $providerItems['private-persisted-query'][1] = '/(?<!You are not authorized to see this content)/';
        return $providerItems;
    }

    protected function getResponseComparisonType(): ?int
    {
        return self::RESPONSE_COMPARISON_REGEX;
    }
}
