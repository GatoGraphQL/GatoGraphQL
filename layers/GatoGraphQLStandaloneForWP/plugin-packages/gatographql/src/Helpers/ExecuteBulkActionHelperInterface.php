<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Helpers;

interface ExecuteBulkActionHelperInterface
{
    public function getExecuteActionWithCustomSettingsBulkActionName(string $bulkActionName): string;

    /**
     * @param array<string,int> $entityIDs
     */
    public function getExecuteActionWithCustomSettingsPageURL(
        string $screenID,
        array $entityIDs,
        string $originURL,
        string $sendbackURL,
    ): string;
}
