<?php

declare(strict_types=1);

namespace PoPSchema\Logger\Log;

use PoPSchema\Logger\Constants\LoggerSeverity;
use PoPSchema\Logger\Constants\LoggerSigns;
use PoPSchema\Logger\Module;
use PoPSchema\Logger\ModuleConfiguration;
use InvalidArgumentException;
use PoP\ComponentModel\App;
use PoP\Root\Services\AbstractBasicService;
use PoPSchema\Logger\Constants\LoggerContext;

use function error_log;
use function str_pad;
use function json_encode;

class Logger extends AbstractBasicService implements LoggerInterface
{
    public const CONTEXT_SEPARATOR = 'CONTEXT: ';

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

    /**
     * @param array<string,mixed>|null $context
     */
    public function log(
        string $severity,
        string $message,
        string $loggerSource = LoggerSources::INFO,
        ?array $context = null,
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

        /** @var string */
        $logsDir = $moduleConfiguration->getLogsDir();
        $logFile = $logsDir . \DIRECTORY_SEPARATOR . $this->generateLogFilename($loggerSource);
        $hasLogFile = $this->maybeCreateLogFile($logFile);
        if (!$hasLogFile) {
            return;
        }

        $message = $this->getMessageWithLogSeverity($severity, $message);
        $this->logMessage($logFile, $message, $severity, $context);
    }

    /**
     * @see https://stackoverflow.com/a/7655379
     * @param array<string,mixed>|null $context
     */
    protected function logMessage(
        string $logFile,
        string $message,
        string $severity,
        ?array $context = null,
    ): void {
        /**
         * Use an ISO 8601 date string in local (WordPress) timezone.
         */
        $date = date('Y-m-d H:i:s');

        if ($context !== null && $context !== []) {
            $message .= $this->__(' ', 'logger') . LoggerContext::LOG_ENTRY_CONTEXT_SEPARATOR . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

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
     * @param array<string,mixed> $options
     */
    protected function generateLogFilename(string $loggerSource, array $options = []): string
    {
        return "$loggerSource.log";
    }

    protected function getMessageWithLogSeverity(string $severity, string $message): string
    {
        if (!in_array($severity, LoggerSeverity::ALL)) {
            throw new InvalidArgumentException(sprintf('Invalid severity: "%s"', $severity));
        }

        if ($this->addSpacePaddingToLogSeverity()) {
            $padLength = max(array_map('strlen', LoggerSeverity::ALL));
            $messageSeverity = str_pad($severity, $padLength);
        } else {
            $messageSeverity = $severity;
        }

        $message = sprintf(
            $this->__('%s %s', 'gatographql'),
            $messageSeverity,
            $message,
        );

        if ($this->addLoggerSignToMessage()) {
            $message = sprintf(
                $this->__('%s %s', 'gatographql'),
                $this->getLoggerSeveritySign($severity),
                $message,
            );
        }

        return $message;
    }

    protected function addLoggerSignToMessage(): bool
    {
        return false;
    }

    protected function addSpacePaddingToLogSeverity(): bool
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
