<?php

declare(strict_types=1);

namespace PoPSchema\Logger\Log;

use PoPSchema\Logger\Enums\LoggerSeverity;
use GatoGraphQL\GatoGraphQL\PluginApp;

use function error_log;

class SystemLogger implements SystemLoggerInterface
{
    public function log(string $message): void
    {
        error_log(sprintf(
            LoggerSeverity::ERROR->sign() . ' [%s] %s',
            PluginApp::getMainPlugin()->getPluginName(),
            $message
        ));
    }
}
