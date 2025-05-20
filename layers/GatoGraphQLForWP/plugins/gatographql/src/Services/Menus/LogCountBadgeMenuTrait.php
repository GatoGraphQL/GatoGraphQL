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

        $logCount = $this->getLogEntryCounterSettingsManager()->getLogCount($moduleConfiguration->enableLogCountBadgesBySeverity());
        if ($logCount === 0) {
            return null;
        }

        return '<span class="awaiting-mod update-plugins remaining-tasks-badge"><span class="count-' . $logCount . '">' . $logCount . '</span></span>';
    }
}
