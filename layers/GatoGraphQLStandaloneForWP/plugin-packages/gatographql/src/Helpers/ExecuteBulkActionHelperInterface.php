<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Helpers;

interface ExecuteBulkActionHelperInterface
{
    public function getExecuteActionWithCustomSettingsBulkActionName(string $bulkActionName): string;

    /**
     * @param array<string|int> $entityIDs
     * @param string[] $requestParamsToSkip Params of the originating request not to carry along to the custom settings form (and back)
     */
    public function getExecuteActionWithCustomSettingsPageURL(
        string $screenID,
        array $entityIDs,
        string $originURL,
        string $sendbackURL,
        array $requestParamsToSkip = [],
    ): string;
}
