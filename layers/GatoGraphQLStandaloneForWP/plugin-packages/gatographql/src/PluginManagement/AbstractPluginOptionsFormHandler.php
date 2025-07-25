<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\PluginManagement;

use GatoGraphQL\GatoGraphQL\PluginManagement\PluginOptionsFormHandler as UpstreamPluginOptionsFormHandler;
use PoP\ComponentModel\App;

abstract class AbstractPluginOptionsFormHandler extends UpstreamPluginOptionsFormHandler
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

        // Check we are executing the bulk action with the custom settings
        $bulkAction = App::request('action') ?? App::query('action') ?? App::request('action2') ?? App::query('action2');
        if (!in_array($bulkAction, $this->getExecuteActionWithCustomSettingsBulkActions())) {
            return parent::maybeOverrideValueFromForm($value, $module, $option);
        }

        $executeAction = App::request('execute_action') ?? App::query('execute_action', false);
        if (!$executeAction) {
            return parent::maybeOverrideValueFromForm($value, $module, $option);
        }

        return $this->doOverrideValueFromForm($value, $module, $option);
    }

    /**
     * @return string[]
     */
    protected function getSupportedBulkActionPages(): array
    {
        $pages = [];
        
        if ($this->supportBulkActionsOnCustomPosts()) {
            $pages[] = 'edit.php'; // Posts
        }
        
        if ($this->supportBulkActionsOnMedia()) {
            $pages[] = 'upload.php'; // Media
        }
        
        if ($this->supportBulkActionsOnTaxonomies()) {
            $pages[] = 'edit-tags.php'; // Taxonomies
        }
        
        return $pages;
    }

    protected function supportBulkActionsOnCustomPosts(): bool
    {
        return true;
    }

    protected function supportBulkActionsOnMedia(): bool
    {
        return true;
    }

    protected function supportBulkActionsOnTaxonomies(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    abstract protected function getExecuteActionWithCustomSettingsBulkActions(): array;
}
