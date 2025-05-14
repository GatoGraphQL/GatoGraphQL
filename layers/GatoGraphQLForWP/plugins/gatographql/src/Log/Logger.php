<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

use GatoGraphQL\GatoGraphQL\Constants\LoggerSeverity;
use GatoGraphQL\GatoGraphQL\Constants\LoggerSigns;
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

    public function log(string $severity, string $message): void
    {
        // Check if the Log is enabled, via the Settings
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableLogs()) {
            return;
        }

        if (!in_array($severity, $moduleConfiguration->enableLogsBySeverity())) {
            return;
        }

        $this->logMessage($severity, $message);
    }

    /**
     * @see https://stackoverflow.com/a/7655379
     */
    protected function logMessage(string $severity, string $message): void
    {
        $logFile = PluginEnvironment::getLogsFilePath(LoggerFiles::INFO);
        $hasLogFile = $this->maybeCreateLogFile($logFile);
        if (!$hasLogFile) {
            return;
        }

        if ($this->addSeverityToMessage()) {
            $message = $this->getMessageWithLogSeverity($severity, $message);
        }
        
        $date = date('Y-m-d H:i:s');
        error_log(sprintf(
            '[%s] %s' . PHP_EOL,
            $date,
            $message
        ), 3, $logFile);
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

        if (!$this->addLoggerSignToMessage($severity, $message)) {
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
        return true;
    }

    protected function getLoggerSeveritySign(string $severity): string
    {
        return match ($severity) {
            LoggerSeverity::ERROR => LoggerSigns::ERROR,
            LoggerSeverity::INFO => LoggerSigns::INFO,
            LoggerSeverity::SUCCESS => LoggerSigns::SUCCESS,
            LoggerSeverity::WARNING => LoggerSigns::WARNING,
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
