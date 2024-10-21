<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase;

/**
 * Test that disabling an endpoint's module makes the endpoint inaccessible
 */
abstract class AbstractEndpointFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase extends AbstractFixtureEnableDisableModuleWordPressAuthenticatedUserWebserverRequestTestCase
{
    protected static function getExpectedContentType(bool $enabled): string
    {
        if (!$enabled) {
            return 'text/html';
        }
        return parent::getExpectedContentType($enabled);
    }

    // Disabled because WordPress doesn't show a 404, but does a redirect
    // protected function getExpectedResponseStatusCode(): int
    // {
    //     $dataName = $this->getDataName();
    //     if (str_ends_with($dataName, ':disabled')) {
    //         return 404;
    //     }
    //     return parent::getExpectedResponseStatusCode();
    // }

    /**
     * @param array<string,array<string,mixed>> $moduleEntries
     * @return array<string,array<string,mixed>>
     */
    protected static function customizeModuleEntries(array $moduleEntries): array
    {
        foreach ($moduleEntries as $entryName => $entry) {
            // We don't care about the HTML content, just that it's HTML
            $entry['response-disabled'] = null;
            $moduleEntries[$entryName] = $entry;
        }
        return $moduleEntries;
    }
}
