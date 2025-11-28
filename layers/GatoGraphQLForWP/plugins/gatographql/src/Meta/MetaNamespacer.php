<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Meta;

use GatoGraphQL\GatoGraphQL\PluginApp;

class MetaNamespacer implements MetaNamespacerInterface
{
    public function namespaceMetaKey(string $metaKey, bool $prefixUnderscore = true): string
    {
        $namespace = PluginApp::getMainPlugin()->getPluginNamespace();
        return ($prefixUnderscore ? '_' : '') . $namespace . '-' . $metaKey;
    }
}
