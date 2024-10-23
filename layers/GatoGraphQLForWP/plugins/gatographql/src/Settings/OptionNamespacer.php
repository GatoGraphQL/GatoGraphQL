<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\PluginApp;

class OptionNamespacer implements OptionNamespacerInterface
{
    public function namespaceOption(string $option): string
    {
        $namespace = PluginApp::getMainPlugin()->getPluginWPConfigConstantNamespace();
        return $namespace . '-' . $option;
    }
}
