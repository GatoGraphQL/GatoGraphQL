<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginDataNaming;

class OptionNamespacer implements OptionNamespacerInterface
{
    public function namespaceOption(string $option): string
    {
        $namespace = PluginApp::getMainPlugin()->getPluginNamespace();
        return PluginDataNaming::namespaceOptionName($namespace, $option);
    }
}
