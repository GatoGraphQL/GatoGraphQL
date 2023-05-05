<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

class AccessPasswordProtectedPersistedQuerySourceSuccessQueryExecutionFixtureWebserverRequestTest extends AbstractAccessPasswordProtectedPersistedQueryQueryExecutionFixtureWebserverRequestTestCase
{
    protected function getResponseFixtureFolder(): string
    {
        return __DIR__ . '/fixture-password-protected-persisted-queries-success';
    }

    protected function viewSource(): bool
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
    protected function customizeProviderEndpointEntries(array $providerItems): array
    {
        // expectedContentType
        $providerItems['password-protected-persisted-query'][0] = 'text/html';
        /**
         * Expect to NOT find the "This content is password protected" message
         *
         * @see function `get_the_password_form` in wp-includes/post-template.php
         */
        $providerItems['password-protected-persisted-query'][1] = '/(?<!This content is password protected)/';
        return $providerItems;
    }

    protected function getResponseComparisonType(): ?int
    {
        return self::RESPONSE_COMPARISON_REGEX;
    }
}
