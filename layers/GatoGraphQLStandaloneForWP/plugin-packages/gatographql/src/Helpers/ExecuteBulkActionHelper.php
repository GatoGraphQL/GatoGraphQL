<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Helpers;

use GatoGraphQLStandalone\GatoGraphQL\Constants\Params;

use PoP\ComponentModel\Configuration\RequestHelpers;
use PoP\ComponentModel\Constants\FrameworkParams;
use function admin_url;

class ExecuteBulkActionHelper implements ExecuteBulkActionHelperInterface
{
    public function getExecuteTranslationWithCustomSettingsBulkActionName(string $bulkActionName): string
    {
        return $bulkActionName . '-custom';
    }

    public function getExecuteActionWithCustomSettingsPageURL(
        string $screenID,
        array $entityIDs,
        string $originURL,
        string $sendbackURL,
    ): string {
        $customBulkActionURLPlaceholder = 'admin.php?page=%s&%s=%s&%s=%s&%s=%s';
        if (RequestHelpers::isRequestingXDebug()) {
            $customBulkActionURLPlaceholder .= '&' . FrameworkParams::XDEBUG_TRIGGER . '=1';
        }
        return admin_url(sprintf(
            $customBulkActionURLPlaceholder,
            $screenID,
            Params::BULK_ACTION_SELECTED_IDS,
            implode(',', $entityIDs),
            Params::BULK_ACTION_ORIGIN_URL,
            rawurlencode($originURL),
            Params::BULK_ACTION_ORIGIN_SENDBACK_URL,
            rawurlencode($sendbackURL)
        ));
    }
}
