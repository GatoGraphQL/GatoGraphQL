<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Overrides\Logger\Log;

use GatoGraphQL\GatoGraphQL\Facades\LogEntryCounterSettingsManagerFacade;
use GatoGraphQL\GatoGraphQL\Log\Controllers\FileHandler\File;
use GatoGraphQL\GatoGraphQL\Settings\LogEntryCounterSettingsManagerInterface;
use PoPSchema\Logger\Log\Logger as UpstreamLogger;

class Logger extends UpstreamLogger
{
    private ?LogEntryCounterSettingsManagerInterface $logEntryCounterSettingsManager = null;

    final protected function getLogEntryCounterSettingsManager(): LogEntryCounterSettingsManagerInterface
    {
        return $this->logEntryCounterSettingsManager ??= LogEntryCounterSettingsManagerFacade::getInstance();
    }

    /**
     * Generate the full name of a file based on source and date values.
     *
     * @param string $loggerSource The source property of a log entry, which determines the filename.
     * @param array<string,mixed> $options 'time': The time of the log entry as a Unix timestamp.
     */
    protected function generateLogFilename(string $loggerSource, array $options = []): string
    {
        $time = $options['time'] ?? time();
        $file_id = File::generate_file_id($loggerSource, null, $time);
        $hash = File::generate_hash($file_id);

        return "$file_id-$hash.log";
    }

    /**
     * Increase the log count for the given severity.
     */
    protected function logMessage(
        string $logFile,
        string $message,
        string $severity,
    ): void {
        parent::logMessage($logFile, $message, $severity);

        $this->getLogEntryCounterSettingsManager()->increaseLogCount($severity);
    }
}
