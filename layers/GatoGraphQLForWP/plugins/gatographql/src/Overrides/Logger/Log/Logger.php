<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Overrides\Logger\Log;

use GatoGraphQL\GatoGraphQL\Log\Controllers\FileHandler\File;
use PoPSchema\Logger\Log\Logger as UpstreamLogger;

class Logger extends UpstreamLogger
{
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
}
