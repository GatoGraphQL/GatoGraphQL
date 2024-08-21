<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Log;

use Exception;
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
        $logFile = PluginEnvironment::getLogsFilePath('info.log');
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

    public function getExceptionMessage(Exception $exception): string
    {
        return sprintf(
            '%s - Stack trace: %s',
            $exception->getMessage(),
            str_replace(PHP_EOL, '\n', $exception->getTraceAsString())
        );
    }
}
