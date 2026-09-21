<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Meta;

use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\PluginSkeleton\PluginDataNaming;

class MetaNamespacer implements MetaNamespacerInterface
{
    public function namespaceMetaKey(string $metaKey, bool $prefixUnderscore = true): string
    {
        $namespace = PluginApp::getMainPlugin()->getPluginNamespace();
        return PluginDataNaming::namespaceMetaKey($namespace, $metaKey, $prefixUnderscore);
    }
}
