<?php

declare(strict_types=1);

namespace PoPSchema\Logger\Log;

use DateTimeInterface;
use PoPSchema\Logger\Constants\LoggerContext;
use PoPSchema\Logger\Enums\LoggerSeverity;
use PoPSchema\Logger\Module;
use PoPSchema\Logger\ModuleConfiguration;
use PoP\ComponentModel\App;
use PoP\Root\Services\AbstractBasicService;

use function error_log;
use function json_encode;
use function str_pad;

class Logger extends AbstractBasicService implements LoggerInterface
{
    public const CONTEXT_SEPARATOR = 'CONTEXT: ';

    /**
     * When `true`, every log entry is prefixed with "[DRY-RUN]", so that
     * queries executed as a dry-run are distinguishable in the logs.
     */
    private bool $isDryRun = false;

    private ?SystemLoggerInterface $systemLogger = null;

    public function setDryRun(bool $isDryRun): void
    {
        $this->isDryRun = $isDryRun;
    }

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
        LoggerSeverity $severity,
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

        if (!in_array($severity, $moduleConfiguration->enableLogsBySeverity(), true)) {
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
        LoggerSeverity $severity,
        ?array $context = null,
    ): void {
        /**
         * Use an ISO 8601 date string in local (WordPress) timezone.
         */
        $date = date(DateTimeInterface::ATOM);

        if ($context !== null && $context !== []) {
            $message .= $this->__(' ', 'gatographql') . LoggerContext::LOG_ENTRY_CONTEXT_SEPARATOR . json_encode($context, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
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

    protected function getMessageWithLogSeverity(LoggerSeverity $severity, string $message): string
    {
        if ($this->isDryRun) {
            $message = sprintf(
                $this->__('%s %s', 'gatographql'),
                '[DRY-RUN]',
                $message,
            );
        }

        if ($this->addSpacePaddingToLogSeverity()) {
            $padLength = max(array_map(
                fn (LoggerSeverity $loggerSeverity): int => strlen($loggerSeverity->value),
                LoggerSeverity::cases()
            ));
            $messageSeverity = str_pad($severity->value, $padLength);
        } else {
            $messageSeverity = $severity->value;
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

    protected function getLoggerSeveritySign(LoggerSeverity $severity): string
    {
        return $severity->sign();
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
