<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\PluginManagement;

use GatoGraphQLStandalone\GatoGraphQL\Constants\Params;
use GatoGraphQL\GatoGraphQL\Facades\Registries\SystemModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\PluginManagement\PluginOptionsFormHandler as UpstreamPluginOptionsFormHandler;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\SettingsMenuPage;
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
        if (!in_array($bulkAction, $this->getExecuteActionWithCustomSettingsBulkActionNames())) {
            return parent::maybeOverrideValueFromForm($value, $module, $option);
        }

        $executeAction = App::request(Params::BULK_ACTION_EXECUTE) ?? App::query(Params::BULK_ACTION_EXECUTE, false);
        if (!$executeAction) {
            return parent::maybeOverrideValueFromForm($value, $module, $option);
        }

        if (!$this->checkIsExpectedSubmittedExecuteActionForm($module, $option)) {
            return parent::maybeOverrideValueFromForm($value, $module, $option);
        }

        return $this->doOverrideValueFromForm($value, $module, $option);
    }

    protected function checkIsExpectedSubmittedExecuteActionForm(string $module, string $option): bool
    {
        // Check this option is added to the target form
        $moduleRegistry = SystemModuleRegistryFacade::getInstance();
        $moduleResolver = $moduleRegistry->getModuleResolver($module);
        $settings = $moduleResolver->getSettings($module);
        $setting = array_values(array_filter(
            $settings,
            fn (array $setting) => ($setting[Properties::INPUT] ?? null) === $option
        ))[0] ?? null;
        if ($setting === null) {
            return false;
        }

        $formTargets = $setting[Properties::FORM_TARGETS] ?? [];
        if ($formTargets === []) {
            return false;
        }

        $formOrigin = App::request(SettingsMenuPage::FORM_ORIGIN);
        return in_array($formOrigin, $formTargets);
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

        if ($this->supportBulkActionsOnUsers()) {
            $pages[] = 'users.php'; // Users
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

    protected function supportBulkActionsOnUsers(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    abstract protected function getExecuteActionWithCustomSettingsBulkActionNames(): array;
}
