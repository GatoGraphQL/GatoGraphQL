<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Helpers;

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
        $customBulkActionURLPlaceholder = 'admin.php?page=%1$s&bulk_action_selected_ids=%2$s&bulk_action_origin_url=%3$s&bulk_action_origin_sendback_url=%4$s';        
        return admin_url(sprintf(
            $customBulkActionURLPlaceholder,
            $screenID,
            implode(',', $entityIDs),
            rawurlencode($originURL),
            rawurlencode($sendbackURL)
        ));
    }
}
