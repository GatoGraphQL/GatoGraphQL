<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\PluginManagement;

use GatoGraphQL\GatoGraphQL\PluginManagement\PluginOptionsFormHandler as UpstreamPluginOptionsFormHandler;

class PluginOptionsFormHandler extends UpstreamPluginOptionsFormHandler
{
    /**
     * Also override the value from the form when doing
     * "Execution action with custom settings".
     *
     * For that, it is enabled in all pages supporting bulk actions.
     */
    public function maybeOverrideValueFromForm(
        mixed $value,
        string $module,
        string $option,
    ): mixed {
        global $pagenow;
        if (!in_array($pagenow, $this->getSupportedBulkActionPages())) {
            return parent::maybeOverrideValueFromForm($value, $module, $option);
        }

        return $this->doOverrideValueFromForm($value, $module, $option);
    }

    /**
     * @return string[]
     */
    protected function getSupportedBulkActionPages(): array
    {
        return [
            'edit.php', // Posts
            'upload.php', // Media
            'edit-tags.php', // Taxonomies
        ];
    }
}
