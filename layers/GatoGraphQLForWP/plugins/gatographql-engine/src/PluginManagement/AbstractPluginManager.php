<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\PluginManagement;

abstract class AbstractPluginManager
{
    protected function printAdminNoticeErrorMessage(string $errorMessage): void
    {
        \add_action('admin_notices', function () use ($errorMessage): void {
            $adminNotice_safe = sprintf(
                '<div class="notice notice-error"><p>%s</p></div>',
                $errorMessage
            );
            echo $adminNotice_safe;
        });
    }
}
