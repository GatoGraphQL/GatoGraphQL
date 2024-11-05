<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Helpers;

use GatoGraphQL\GatoGraphQL\PluginApp;

class HookNamespacingHelpers
{
    public function namespaceHook(string $hookName): string
    {
        return PluginApp::getMainPlugin()->getPluginNamespace() . ':' . $hookName;
    }
}
