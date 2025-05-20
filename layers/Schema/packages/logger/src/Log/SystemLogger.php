<?php

declare(strict_types=1);

namespace PoPSchema\Logger\Log;

use PoPSchema\Logger\Constants\LoggerSigns;
use GatoGraphQL\GatoGraphQL\PluginApp;

use function error_log;

class SystemLogger implements SystemLoggerInterface
{
    public function log(string $message): void
    {
        error_log(sprintf(
            LoggerSigns::ERROR . ' [%s] %s',
            PluginApp::getMainPlugin()->getPluginName(),
            $message
        ));
    }
}
