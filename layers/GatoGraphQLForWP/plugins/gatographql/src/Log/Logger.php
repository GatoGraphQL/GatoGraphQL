<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

use DateTimeInterface;
use GatoGraphQL\GatoGraphQL\Constants\LoggerSeverity;
use GatoGraphQL\GatoGraphQL\Constants\LoggerSigns;
use GatoGraphQL\GatoGraphQL\Log\Controllers\FileHandler\File;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\PluginEnvironment;
use InvalidArgumentException;
use PoP\ComponentModel\App;
use PoP\Root\Services\AbstractBasicService;

use function error_log;
use function str_pad;

class Logger extends AbstractBasicService implements LoggerInterface
{
    private ?SystemLoggerInterface $systemLogger = null;

    final protected function getSystemLogger(): SystemLoggerInterface
    {
        if ($this->systemLogger === null) {
            /** @var SystemLoggerInterface */
            $systemLogger = $this->instanceManager->getInstance(SystemLoggerInterface::class);
            $this->systemLogger = $systemLogger;
        }
        return $this->systemLogger;
    }

    public function log(
        string $severity,
        string $message,
        string $loggerSource = LoggerSources::INFO,
    ): void {
        // Check if the Log is enabled, via the Settings
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableLogs()) {
            return;
        }

        if (!in_array($severity, $moduleConfiguration->enableLogsBySeverity())) {
            return;
        }

        $this->logMessage($severity, $message, $loggerSource);
    }

    /**
     * @see https://stackoverflow.com/a/7655379
     */
    protected function logMessage(
        string $severity,
        string $message,
        string $loggerSource = LoggerSources::INFO,
    ): void {
        $logFile = PluginEnvironment::getLogsDir() . \DIRECTORY_SEPARATOR . $this->generateLogFilename($loggerSource, time());
        $hasLogFile = $this->maybeCreateLogFile($logFile);
        if (!$hasLogFile) {
            return;
        }

        if ($this->addSeverityToMessage()) {
            $message = $this->getMessageWithLogSeverity($severity, $message);
        }

        /**
         * Use an ISO 8601 date string in local (WordPress) timezone.
         */
        $date = date(DateTimeInterface::ATOM);
        error_log(sprintf(
            '%s %s' . PHP_EOL,
            $date,
            $message
        ), 3, $logFile);
    }

    /**
     * Generate the full name of a file based on source and date values.
     *
     * @param string $loggerSource The source property of a log entry, which determines the filename.
     * @param int    $time   The time of the log entry as a Unix timestamp.
     */
    private function generateLogFilename(string $loggerSource, int $time): string
    {
        $file_id = File::generate_file_id($loggerSource, null, $time);
        $hash = File::generate_hash($file_id);

        return "$file_id-$hash.log";
    }

    protected function addSeverityToMessage(): bool
    {
        return true;
    }

    protected function getMessageWithLogSeverity(string $severity, string $message): string
    {
        if (!in_array($severity, LoggerSeverity::ALL)) {
            throw new InvalidArgumentException(sprintf('Invalid severity: "%s"', $severity));
        }

        $padLength = max(array_map('strlen', LoggerSeverity::ALL));

        if (!$this->addLoggerSignToMessage()) {
            return sprintf(
                \__('%s %s', 'gatographql'),
                str_pad($severity, $padLength),
                $message,
            );
        }

        return sprintf(
            \__('%s %s %s', 'gatographql'),
            $this->getLoggerSeveritySign($severity),
            str_pad($severity, $padLength),
            $message,
        );
    }

    protected function addLoggerSignToMessage(): bool
    {
        return false;
    }

    protected function getLoggerSeveritySign(string $severity): string
    {
        return match ($severity) {
            LoggerSeverity::ERROR => LoggerSigns::ERROR,
            LoggerSeverity::WARNING => LoggerSigns::WARNING,
            LoggerSeverity::INFO => LoggerSigns::INFO,
            LoggerSeverity::DEBUG => LoggerSigns::DEBUG,
            default => throw new InvalidArgumentException(sprintf('Invalid severity: "%s"', $severity)),
        };
    }

    protected function maybeCreateLogFile(string $filename): bool
    {
        if (file_exists($filename)) {
            return true;
        }

        $dir = \dirname($filename);
        if (!is_dir($dir) && @mkdir($dir, 0777, true) === false) {
            $this->getSystemLogger()->log('Can\'t create directory to store log files, under path ' . $dir);
            return false;
        }

        $handle = fopen($filename, "w");
        if ($handle === false) {
            $this->getSystemLogger()->log('Can\'t create log file under path ' . $filename);
            return false;
        }
        fclose($handle);

        return true;
    }
}
