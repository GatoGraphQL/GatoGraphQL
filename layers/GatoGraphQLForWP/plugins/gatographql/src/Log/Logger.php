<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

use GatoGraphQL\GatoGraphQL\PluginEnvironment;

class Logger implements LoggerInterface
{
    public function logError(string $message): void
    {
        \error_log(sprintf(
            '[Gato GraphQL] %s',
            $message
        ));
    }

    /**
     * @see https://stackoverflow.com/a/7655379
     */
    public function logInfo(string $message): void
    {
        $logFile = PluginEnvironment::getLogsFilePath('logs.log');
        $hasLogFile = $this->maybeCreateLogFile($logFile);
        if (!$hasLogFile) {
            return;
        }

        \error_log(sprintf(
            '[INFO] %s',
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
            $this->logError('Can\'t create directory to store log files, under path ' . $dir);
            return false;
        }

        $handle = fopen($filename, "w");
        if ($handle === false) {
            $this->logError('Can\'t create log file under path ' . $filename);
            return false;
        }
        fclose($handle);

        return true;
    }
}
