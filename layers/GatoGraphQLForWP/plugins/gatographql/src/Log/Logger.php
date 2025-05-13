<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

use GatoGraphQL\GatoGraphQL\Constants\LoggerSeverity;
use GatoGraphQL\GatoGraphQL\Constants\LoggerSigns;
use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginEnvironment;
use InvalidArgumentException;
use PoP\ComponentModel\App;

class Logger implements LoggerInterface
{
    public function log(string $severity, string $message): void
    {
        if ($severity === LoggerSeverity::ERROR) {
            $this->logSystemError($message);
        }

        $sign = match ($severity) {
            LoggerSeverity::ERROR => LoggerSigns::ERROR,
            LoggerSeverity::INFO => LoggerSigns::INFO,
            LoggerSeverity::SUCCESS => LoggerSigns::SUCCESS,
            LoggerSeverity::WARNING => LoggerSigns::WARNING,
            default => throw new InvalidArgumentException(sprintf('Invalid severity: "%s"', $severity)),
        };
        $this->logOwnStream(
            sprintf(
                \__('%s %s', 'gatographql'),
                $sign,
                $message,
            )
        );
    }
    
    protected function logSystemError(string $message): void
    {
        \error_log(sprintf(
            LoggerSigns::ERROR . ' [%s] %s',
            PluginApp::getMainPlugin()->getPluginName(),
            $message
        ));
    }

    /**
     * @see https://stackoverflow.com/a/7655379
     */
    protected function logOwnStream(string $message): void
    {
        // Check if the Log is enabled, via the Settings
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableLogs()) {
            return;
        }

        $logFile = PluginEnvironment::getLogsFilePath(LoggerFiles::INFO);
        $hasLogFile = $this->maybeCreateLogFile($logFile);
        if (!$hasLogFile) {
            return;
        }

        $date = date('Y-m-d H:i:s');
        \error_log(sprintf(
            '[%s] %s' . PHP_EOL,
            $date,
            $message
        ), 3, $logFile);
    }

    protected function maybeCreateLogFile(string $filename): bool
    {
        if (file_exists($filename)) {
            return true;
        }

        $dir = \dirname($filename);
        if (!is_dir($dir) && @mkdir($dir, 0777, true) === false) {
            $this->logSystemError('Can\'t create directory to store log files, under path ' . $dir);
            return false;
        }

        $handle = fopen($filename, "w");
        if ($handle === false) {
            $this->logSystemError('Can\'t create log file under path ' . $filename);
            return false;
        }
        fclose($handle);

        return true;
    }
}
