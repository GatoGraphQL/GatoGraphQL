<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Menus;

use GatoGraphQL\GatoGraphQL\Facades\LogEntryCounterSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Settings\LogEntryCounterSettingsManagerInterface;
use PoP\Root\App;

trait LogCountBadgeMenuTrait
{
    private ?LogEntryCounterSettingsManagerInterface $logEntryCounterSettingsManager = null;

    final protected function getLogEntryCounterSettingsManager(): LogEntryCounterSettingsManagerInterface
    {
        return $this->logEntryCounterSettingsManager ??= LogEntryCounterSettingsManagerFacade::getInstance();
    }

    protected function getLogCountBadge(): ?string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableLogCountBadges()) {
            return null;
        }

        $severities = $moduleConfiguration->enableLogCountBadgesBySeverity();
        if ($severities === []) {
            return null;
        }

        $logCountBySeverity = $this->getLogEntryCounterSettingsManager()->getLogCountBySeverity($severities);
        if ($logCountBySeverity === []) {
            return null;
        }

        $severitiesWithLogCount = array_keys(array_filter($logCountBySeverity, fn (int $logCount): bool => $logCount > 0));
        if ($severitiesWithLogCount === []) {
            return null;
        }

        $highestLevelSeverity = $this->getLogEntryCounterSettingsManager()->sortSeveritiesByHighestLevel($severitiesWithLogCount)[0];
        $severityClass = 'badge-severity-' . strtolower($highestLevelSeverity);
        $logCount = (string) $logCountBySeverity[$highestLevelSeverity];
        if (count($severitiesWithLogCount) > 1) {
            $logCount .= '+';
        }

        return '<span class="awaiting-mod update-plugins remaining-tasks-badge ' . $severityClass . '"><span class="count-' . $logCount . '">' . $logCount . '</span></span>';
    }
}
